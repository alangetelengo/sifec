/**
 * Signature locale PKCS#12 (.p12) pour SIFEC — ECDSA P-256.
 * Prefer SubtleCrypto (HTTPS / localhost) ; repli elliptic.js si HTTP non sécurisé.
 */
(function (window) {
    'use strict';

    function binaryStringToBytes(binaryString) {
        var bytes = new Uint8Array(binaryString.length);
        for (var i = 0; i < binaryString.length; i++) {
            bytes[i] = binaryString.charCodeAt(i);
        }
        return bytes;
    }

    function bytesToBinaryString(bytes) {
        var CHUNK = 0x8000;
        var parts = [];
        for (var i = 0; i < bytes.length; i += CHUNK) {
            parts.push(String.fromCharCode.apply(null, bytes.subarray(i, i + CHUNK)));
        }
        return parts.join('');
    }

    function bytesToHex(bytes) {
        return Array.from(bytes)
            .map(function (b) { return b.toString(16).padStart(2, '0'); })
            .join('');
    }

    function concatBytes() {
        var arrays = Array.prototype.slice.call(arguments);
        var total = arrays.reduce(function (sum, a) { return sum + a.length; }, 0);
        var result = new Uint8Array(total);
        var offset = 0;
        arrays.forEach(function (a) {
            result.set(a, offset);
            offset += a.length;
        });
        return result;
    }

    function derLength(len) {
        if (len < 0x80) return [len];
        var bytes = [];
        var n = len;
        while (n > 0) {
            bytes.unshift(n & 0xff);
            n >>= 8;
        }
        return [0x80 | bytes.length].concat(bytes);
    }

    function derInteger(bytes) {
        var i = 0;
        while (i < bytes.length - 1 && bytes[i] === 0x00) i++;
        var trimmed = bytes.slice(i);
        if (trimmed[0] & 0x80) {
            trimmed = concatBytes(new Uint8Array([0x00]), trimmed);
        }
        return concatBytes(new Uint8Array([0x02].concat(derLength(trimmed.length))), trimmed);
    }

    function ecdsaRawToDer(raw) {
        var half = raw.length / 2;
        var r = derInteger(raw.slice(0, half));
        var s = derInteger(raw.slice(half));
        var body = concatBytes(r, s);
        return concatBytes(new Uint8Array([0x30].concat(derLength(body.length))), body);
    }

    function normalizeSerial(s) {
        return String(s || '')
            .replace(/^0x/i, '')
            .replace(/^0+/, '')
            .toLowerCase() || '0';
    }

    function ensureForge() {
        if (typeof window.forge === 'undefined') {
            throw new Error('Bibliothèque de certificat non chargée. Rechargez la page.');
        }
        return window.forge;
    }

    function hasSubtleCrypto() {
        return typeof window.crypto !== 'undefined'
            && window.crypto.subtle
            && typeof window.crypto.subtle.importKey === 'function';
    }

    /**
     * Extrait la clé privée EC (32 octets) depuis un PrivateKeyInfo PKCS#8 (ASN.1 forge).
     */
    function extractEcPrivateKeyHex(forge, keyBagAsn1) {
        if (!keyBagAsn1 || !keyBagAsn1.value || keyBagAsn1.value.length < 3) {
            throw new Error('Structure de clé privée invalide.');
        }
        var privateKeyOctet = keyBagAsn1.value[2];
        var ecDer = typeof privateKeyOctet.value === 'string'
            ? privateKeyOctet.value
            : forge.asn1.toDer(privateKeyOctet).getBytes();

        // Si value est déjà l'OCTET STRING content (cas le plus fréquent)
        var ecAsn1;
        try {
            ecAsn1 = forge.asn1.fromDer(privateKeyOctet.value);
        } catch (e) {
            ecAsn1 = forge.asn1.fromDer(ecDer);
        }

        // ECPrivateKey : version, privateKey OCTET STRING, ...
        var privOctet = ecAsn1.value && ecAsn1.value[1] ? ecAsn1.value[1] : null;
        if (!privOctet || privOctet.value == null) {
            throw new Error('Impossible d’extraire la clé EC du certificat.');
        }
        var hex = forge.util.bytesToHex(privOctet.value);
        while (hex.length < 64) {
            hex = '0' + hex;
        }
        if (hex.length > 64) {
            hex = hex.slice(-64);
        }
        return hex;
    }

    function getElliptic() {
        if (typeof window.elliptic !== 'undefined' && window.elliptic.ec) {
            return window.elliptic;
        }
        if (typeof elliptic !== 'undefined' && elliptic.ec) {
            return elliptic;
        }
        return null;
    }

    /**
     * SHA-256(hashBytes) puis signature ECDSA P-256 → DER hex.
     * Miroir de SubtleCrypto({hash:'SHA-256'}) + openssl_verify(OPENSSL_ALGO_SHA256).
     */
    function signEcdsaP256Fallback(forge, keyBagAsn1, hashBytes) {
        var ellipticLib = getElliptic();
        if (!ellipticLib) {
            throw new Error(
                'Bibliothèque de signature ECDSA absente. Rechargez la page (Ctrl+F5). ' +
                'Si le problème continue, ouvrez SIFEC en HTTPS ou en localhost.'
            );
        }

        var privHex = extractEcPrivateKeyHex(forge, keyBagAsn1);
        var md = forge.md.sha256.create();
        md.update(hashBytes);
        var digestBin = md.digest().getBytes();
        var digestArr = [];
        for (var i = 0; i < digestBin.length; i++) {
            digestArr.push(digestBin.charCodeAt(i) & 0xff);
        }

        var ec = new ellipticLib.ec('p256');
        var key = ec.keyFromPrivate(privHex, 'hex');
        var sig = key.sign(digestArr, { canonical: true });
        var derArr = sig.toDER();
        return bytesToHex(derArr);
    }

    async function signEcdsaP256Subtle(forge, keyBagAsn1, hashBytes) {
        var pkcs8Der = forge.asn1.toDer(keyBagAsn1).getBytes();
        var cryptoKey = await window.crypto.subtle.importKey(
            'pkcs8',
            binaryStringToBytes(pkcs8Der),
            { name: 'ECDSA', namedCurve: 'P-256' },
            false,
            ['sign']
        );
        var rawSignature = await window.crypto.subtle.sign(
            { name: 'ECDSA', hash: { name: 'SHA-256' } },
            cryptoKey,
            binaryStringToBytes(hashBytes)
        );
        return bytesToHex(ecdsaRawToDer(new Uint8Array(rawSignature)));
    }

    function readP12File(file) {
        return new Promise(function (resolve, reject) {
            if (!file) {
                reject(new Error('Sélectionnez votre fichier certificat (.p12).'));
                return;
            }
            var name = String(file.name || '').toLowerCase();
            if (name && !/\.(p12|pfx)$/.test(name)) {
                reject(new Error('Le fichier doit être un certificat .p12 (ou .pfx).'));
                return;
            }
            var reader = new FileReader();
            reader.onload = function () {
                try {
                    var bytes = new Uint8Array(reader.result);
                    if (!bytes.length) {
                        reject(new Error('Fichier certificat vide.'));
                        return;
                    }
                    resolve(bytesToBinaryString(bytes));
                } catch (e) {
                    reject(new Error('Impossible de lire le fichier certificat.'));
                }
            };
            reader.onerror = function () {
                reject(new Error('Impossible de lire le fichier certificat.'));
            };
            reader.readAsArrayBuffer(file);
        });
    }

    function openPkcs12(forge, p12Binary, pin) {
        var asn1;
        try {
            asn1 = forge.asn1.fromDer(p12Binary);
        } catch (e) {
            throw new Error(
                'Fichier .p12 illisible. Retéléchargez-le depuis votre profil (Certificat numérique).'
            );
        }

        var candidates = [String(pin)];
        if (String(pin) !== String(pin).trim()) {
            candidates.push(String(pin).trim());
        }

        var lastErr = null;
        for (var i = 0; i < candidates.length; i++) {
            var password = candidates[i];
            try {
                return forge.pkcs12.pkcs12FromAsn1(asn1, false, password);
            } catch (e1) {
                lastErr = e1;
                try {
                    return forge.pkcs12.pkcs12FromAsn1(asn1, password);
                } catch (e2) {
                    lastErr = e2;
                }
            }
        }

        if (typeof console !== 'undefined' && console.warn) {
            console.warn('[SifecP12Sign] ouverture PKCS#12 échouée', lastErr);
        }

        throw new Error(
            'Passphrase incorrecte ou fichier .p12 invalide. ' +
            'Utilisez le fichier téléchargé depuis votre profil et la passphrase affichée à ce moment-là ' +
            '(chaque nouveau téléchargement génère une nouvelle passphrase).'
        );
    }

    async function signHashHex(p12Binary, pin, hashHex, expectedSerial) {
        var forge = ensureForge();
        if (!pin || !String(pin).trim()) {
            throw new Error('Saisissez la passphrase de votre certificat.');
        }
        if (!hashHex || !/^[0-9a-fA-F]+$/.test(hashHex)) {
            throw new Error('Empreinte document invalide.');
        }

        var p12 = openPkcs12(forge, p12Binary, pin);

        var keyBag = null;
        var shroudedBags = p12.getBags({ bagType: forge.pki.oids.pkcs8ShroudedKeyBag });
        keyBag = shroudedBags[forge.pki.oids.pkcs8ShroudedKeyBag]
            ? shroudedBags[forge.pki.oids.pkcs8ShroudedKeyBag][0]
            : null;
        if (!keyBag) {
            var keyBags = p12.getBags({ bagType: forge.pki.oids.keyBag });
            keyBag = keyBags[forge.pki.oids.keyBag] ? keyBags[forge.pki.oids.keyBag][0] : null;
        }
        if (!keyBag || (!keyBag.key && !keyBag.asn1)) {
            throw new Error('Impossible d’extraire la clé privée du certificat.');
        }

        var certBags = p12.getBags({ bagType: forge.pki.oids.certBag });
        var certBag = certBags[forge.pki.oids.certBag] ? certBags[forge.pki.oids.certBag][0] : null;
        if (!certBag || (!certBag.cert && !certBag.asn1)) {
            throw new Error('Aucun certificat trouvé dans le fichier .p12.');
        }

        var serialFromP12;
        if (certBag.cert) {
            serialFromP12 = normalizeSerial(certBag.cert.serialNumber);
        } else {
            var serialAsn1 = certBag.asn1.value[0].value[1];
            serialFromP12 = normalizeSerial(forge.util.bytesToHex(serialAsn1.value));
        }

        if (expectedSerial) {
            if (normalizeSerial(expectedSerial) !== serialFromP12) {
                throw new Error(
                    'Ce certificat ne correspond pas à votre certificat enregistré. Utilisez uniquement votre certificat personnel.'
                );
            }
        }

        var hashBytes = forge.util.hexToBytes(hashHex);

        if (keyBag.key) {
            var md = forge.md.sha256.create();
            md.update(hashBytes);
            return forge.util.bytesToHex(keyBag.key.sign(md));
        }

        // ECDSA P-256 (cas escrow trust-api)
        if (hasSubtleCrypto()) {
            return await signEcdsaP256Subtle(forge, keyBag.asn1, hashBytes);
        }

        return signEcdsaP256Fallback(forge, keyBag.asn1, hashBytes);
    }

    window.SifecP12Sign = {
        readP12File: readP12File,
        signHashHex: signHashHex,
        normalizeSerial: normalizeSerial,
        hasSubtleCrypto: hasSubtleCrypto,
    };
})(window);
