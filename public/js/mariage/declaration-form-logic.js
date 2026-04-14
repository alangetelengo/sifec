// Variable globale pour gérer le timeout de vérification
let verificationTimeout = null;

// Fonction pour effacer le timeout de vérification
function clearVerificationTimeout() {
    if (verificationTimeout) {
        clearTimeout(verificationTimeout);
        verificationTimeout = null;
    }
}

function verifierActeMariageEpoux(numeroActe) {
    // Effacer tout timeout existant
    clearVerificationTimeout();

    // Validation plus stricte du numéro d'acte
    if (!numeroActe || numeroActe.trim() === '') {
        // Masquer la notification si le champ est vide
        const notificationDiv = document.getElementById('notification_acte_mariage_epoux');
        if (notificationDiv) {
            notificationDiv.style.display = 'none';
        }
        return;
    }

    // Validation du format du numéro d'acte (doit contenir au moins 10 caractères)
    if (numeroActe.trim().length < 10) {
        const notificationDiv = document.getElementById('notification_acte_mariage_epoux');
        if (notificationDiv) {
            notificationDiv.innerHTML = `
                <div class="alert alert-warning">
                    <h6><i class="fa fa-exclamation-triangle"></i> Format invalide</h6>
                    <p>Le numéro d'acte de naissance doit contenir au moins 10 caractères.</p>
                </div>
            `;
            notificationDiv.style.display = 'block';
        }
        return;
    }

    // Attendre 500ms avant de faire la vérification pour éviter les appels multiples
    verificationTimeout = setTimeout(() => {
        executerVerificationActeMariage(numeroActe);
    }, 500);
}

function executerVerificationActeMariage(numeroActe) {

    // Afficher un indicateur de chargement
    const notificationDiv = document.getElementById('notification_acte_mariage_epoux');
    notificationDiv.innerHTML = '<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Vérification en cours...</div>';
    notificationDiv.style.display = 'block';

    // Vérifier la présence du token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        notificationDiv.innerHTML = `
            <div class="alert alert-danger">
                <h6><i class="fa fa-exclamation-circle"></i> Erreur de configuration</h6>
                <p>Token CSRF manquant. Veuillez recharger la page.</p>
            </div>
        `;
        notificationDiv.style.display = 'block';
        return;
    }

    // Requête Ajax pour vérifier l'acte de mariage
    fetch(`/acteMariage/acte/search/${numeroActe}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status} - ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.code === "200") {
            // Acte trouvé et tout est OK
            const acte = data.acte;
            const dateCelebration = acte.date_celebration ? new Date(acte.date_celebration).toLocaleDateString('fr-FR') : 'Non spécifiée';
            const nomEtatCivil = acte.etat_civil ? acte.etat_civil.nom_institution || 'Non spécifié' : 'Non spécifié';

            notificationDiv.innerHTML = `
                <div class="alert alert-success">
                    <h6><i class="fa fa-check-circle"></i> Acte de mariage trouvé</h6>
                    <p>${data.message.optionMariage}</p>
                    <div class="mt-2">
                        <strong>Détails de l'acte :</strong>
                        <ul class="mb-2">
                            <li>Code acte : ${acte.code_acte}</li>
                            <li>Date d'émission : ${new Date(acte.date_emission).toLocaleDateString('fr-FR')}</li>
                            <li>Option de mariage : ${acte.option_mariage}</li>
                        </ul>
                        ${acte.epouse ? `
                        <strong>Informations complémentaires :</strong>
                        <ul class="mb-0">
                            <li><strong>Identité de l'épouse :</strong> ${acte.epouse.nom_complet || 'Non spécifiée'}</li>
                            <li><strong>Date de célébration :</strong> ${dateCelebration}</li>
                            <li><strong>État civil :</strong> ${nomEtatCivil}</li>
                        </ul>
                        ` : ''}
                    </div>
                </div>
            `;
            notificationDiv.style.display = 'block';

            // Permettre l'avancement
            permettreAvancement();

            // Afficher une notification flash
            if (typeof flashAlert !== 'undefined') {
                flashAlert("Acte de mariage trouvé", "success", data.message.optionMariage);
            }

        } else if (data.code === "99") {
            // Acte trouvé mais problème avec l'option de mariage
            const acte = data.acte;
            notificationDiv.innerHTML = `
                <div class="alert alert-warning">
                    <h6><i class="fa fa-exclamation-triangle"></i> Acte de mariage trouvé</h6>
                    <p>${data.message.optionMariage}</p>
                    <div class="mt-2">
                        <strong>Détails de l'acte existant :</strong>
                        <ul class="mb-2">
                            <li>Code acte : ${acte.code_acte}</li>
                            <li>Date d'émission : ${new Date(acte.date_emission).toLocaleDateString('fr-FR')}</li>
                            <li>Option de mariage : ${acte.option_mariage}</li>
                        </ul>
                    </div>
                    <p><strong>Action requise :</strong> Veuillez modifier l'option de mariage du premier mariage ou fournir les documents justificatifs (jugement de divorce ou acte de décès).</p>
                </div>
            `;
            notificationDiv.style.display = 'block';

            // Empêcher l'utilisateur d'avancer
            bloquerAvancement();

            // Afficher une notification flash
            if (typeof flashAlert !== 'undefined') {
                flashAlert("Acte de mariage trouvé", "warning", "L'époux est déjà marié en monogamie. Veuillez fournir les documents justificatifs.");
            }

        } else if (data.code === "404") {
            // Aucun acte trouvé
            notificationDiv.innerHTML = `
                <div class="alert alert-info">
                    <h6><i class="fa fa-info-circle"></i> Aucun acte de mariage trouvé</h6>
                    <p>${data.message.optionMariage || 'Aucun acte de mariage trouvé pour ce code.'}</p>
                    <p>Le processus peut continuer.</p>
                </div>
            `;
            notificationDiv.style.display = 'block';

            // Permettre l'avancement
            permettreAvancement();
        } else {
            // Erreur de recherche
            let errorMessage = data.message || 'Erreur inconnue';
            let alertClass = 'alert-danger';
            let icon = 'fa-exclamation-circle';
            let title = 'Erreur de vérification';

            // Gestion spécifique des erreurs connues
            if (errorMessage.includes('Aucun numéro d\'acte trouvé')) {
                alertClass = 'alert-warning';
                icon = 'fa-exclamation-triangle';
                title = 'Numéro d\'acte non trouvé';
                errorMessage = 'Le numéro d\'acte de naissance saisi n\'existe pas dans la base de données. Veuillez vérifier le numéro et réessayer.';
            } else if (errorMessage.includes('Personne non trouvée')) {
                alertClass = 'alert-warning';
                icon = 'fa-user-times';
                title = 'Personne non trouvée';
                errorMessage = 'Aucune personne correspondant à ce numéro d\'acte de naissance n\'a été trouvée.';
            }

            notificationDiv.innerHTML = `
                <div class="alert ${alertClass}">
                    <h6><i class="fa ${icon}"></i> ${title}</h6>
                    <p>${errorMessage}</p>
                </div>
            `;
            notificationDiv.style.display = 'block';

            // Permettre l'avancement en cas d'erreur
            permettreAvancement();
        }
    })
    .catch(error => {
        console.error('Erreur lors de la vérification:', error);
        notificationDiv.innerHTML = `
            <div class="alert alert-danger">
                <h6><i class="fa fa-exclamation-circle"></i> Erreur de vérification</h6>
                <p>Une erreur s'est produite lors de la vérification de l'acte de mariage.</p>
                <p><strong>Détails :</strong> ${error.message}</p>
                <p>Veuillez vérifier votre connexion et réessayer.</p>
            </div>
        `;
        notificationDiv.style.display = 'block';
    });
}

function bloquerAvancement() {
    // Désactiver le bouton suivant du wizard - essayer plusieurs sélecteurs
    const selectors = [
        '.wizard .actions ul li.next a',
        '.validation-wizard .actions ul li.next a',
        '.wizard-circle .actions ul li.next a',
        '.actions ul li.next a',
        'a[href="#next"]',
        'button[type="submit"]',
        '.btn-next',
        '.wizard-next'
    ];

    let nextButton = null;
    for (const selector of selectors) {
        nextButton = document.querySelector(selector);
        if (nextButton) {
            console.log('Bouton suivant trouvé avec le sélecteur:', selector);
            break;
        }
    }

    if (nextButton) {
        nextButton.style.pointerEvents = 'none';
        nextButton.style.opacity = '0.5';
        nextButton.style.cursor = 'not-allowed';
        nextButton.title = 'Veuillez résoudre le problème avec l\'acte de mariage avant de continuer';

        // Ajouter une classe pour identifier le blocage
        nextButton.classList.add('btn-bloque');

        console.log('Bouton suivant bloqué:', nextButton);
    } else {
        console.warn('Aucun bouton suivant trouvé avec les sélecteurs testés');
    }

    // Ajouter une classe pour identifier le blocage
    document.body.classList.add('mariage-bloque');

    // Afficher un message de blocage visible
    afficherMessageBlocage();
}

function permettreAvancement() {
    // Réactiver le bouton suivant du wizard - essayer plusieurs sélecteurs
    const selectors = [
        '.wizard .actions ul li.next a',
        '.validation-wizard .actions ul li.next a',
        '.wizard-circle .actions ul li.next a',
        '.actions ul li.next a',
        'a[href="#next"]',
        'button[type="submit"]',
        '.btn-next',
        '.wizard-next'
    ];

    let nextButton = null;
    for (const selector of selectors) {
        nextButton = document.querySelector(selector);
        if (nextButton) {
            break;
        }
    }

    if (nextButton) {
        nextButton.style.pointerEvents = 'auto';
        nextButton.style.opacity = '1';
        nextButton.style.cursor = 'pointer';
        nextButton.title = '';
        nextButton.classList.remove('btn-bloque');

        console.log('Bouton suivant réactivé:', nextButton);
    }

    // Retirer la classe de blocage
    document.body.classList.remove('mariage-bloque');

    // Masquer le message de blocage
    masquerMessageBlocage();
}

// Fonction pour afficher un message de blocage visible
function afficherMessageBlocage() {
    // Supprimer l'ancien message s'il existe
    masquerMessageBlocage();

    // Créer le message de blocage
    const messageBlocage = document.createElement('div');
    messageBlocage.id = 'message-blocage-mariage';
    messageBlocage.className = 'alert alert-danger alert-dismissible fade show position-fixed';
    messageBlocage.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
    messageBlocage.innerHTML = `
        <h6><i class="fa fa-exclamation-triangle"></i> Avancement bloqué</h6>
        <p>Vous ne pouvez pas continuer tant que le problème avec l'acte de mariage n'est pas résolu.</p>
        <p><strong>Action requise :</strong> Modifiez l'option de mariage ou fournissez les documents justificatifs.</p>
        <button type="button" class="btn-close" onclick="masquerMessageBlocage()"></button>
    `;

    document.body.appendChild(messageBlocage);
}

// Fonction pour masquer le message de blocage
function masquerMessageBlocage() {
    const messageBlocage = document.getElementById('message-blocage-mariage');
    if (messageBlocage) {
        messageBlocage.remove();
    }
}

// Fonction pour vérifier l'option de mariage sélectionnée
function verifierOptionMariage() {
    const optionMariage = document.getElementById('option_mariage').value;
    const notificationDiv = document.getElementById('notification_acte_mariage_epoux');

    if (optionMariage && document.body.classList.contains('mariage-bloque')) {
        // Si une option de mariage est sélectionnée et qu'il y avait un blocage
        // Vérifier si c'est compatible avec l'acte existant
        if (optionMariage === 'OMRG_0001' || optionMariage === 'OPM_0002') { // Polygamie (OMRG_* ou seed OPM_*)
            notificationDiv.innerHTML = `
                <div class="alert alert-success">
                    <h6><i class="fa fa-check-circle"></i> Option de mariage compatible</h6>
                    <p>L'option <strong>Polygamie</strong> est compatible avec le mariage existant.</p>
                    <p>Le processus peut maintenant continuer.</p>
                </div>
            `;
            permettreAvancement();
        } else if (optionMariage === 'OMRG_0002' || optionMariage === 'OPM_0001') { // Monogamie
            // La monogamie reste problématique
            notificationDiv.innerHTML = `
                <div class="alert alert-warning">
                    <h6><i class="fa fa-exclamation-triangle"></i> Option de mariage incompatible</h6>
                    <p>L'option <strong>Monogamie</strong> n'est pas compatible avec le mariage existant.</p>
                    <p>Veuillez fournir les documents justificatifs (jugement de divorce ou acte de décès).</p>
                </div>
            `;
            bloquerAvancement();
        }
    }
}

// Fonction pour vérifier le changement de situation matrimoniale
function verifierSituationMatrimonialeChange(typePersonne) {
    const situationMatrimoniale = document.getElementById(`sit_matrimoniale_${typePersonne}`).value;
    const notificationDiv = document.getElementById(`notification_acte_mariage_${typePersonne}`);

    console.log(`Changement de situation matrimoniale pour ${typePersonne}:`, situationMatrimoniale);

    // Si le formulaire est bloqué et que l'utilisateur change la situation matrimoniale
    if (document.body.classList.contains('mariage-bloque')) {

        // Codes des situations matrimoniales qui permettent de débloquer
        const situationsCompatibles = [
            'SMAT_0001', // Célibataire
            'SMAT_0002', // Divorcé(e)
            'SMAT_0005', // Veuf/Veuve
            'SMAT_0006'  // Autre situation
        ];

        if (situationsCompatibles.includes(situationMatrimoniale)) {
            // Situation compatible - débloquer
            notificationDiv.innerHTML = `
                <div class="alert alert-success">
                    <h6><i class="fa fa-check-circle"></i> Situation matrimoniale mise à jour</h6>
                    <p>La situation matrimoniale a été modifiée. Le processus peut maintenant continuer.</p>
                    <p><strong>Nouvelle situation :</strong> ${getLibelleSituationMatrimoniale(situationMatrimoniale)}</p>
                </div>
            `;

            permettreAvancement();

            // Afficher une notification flash
            if (typeof flashAlert !== 'undefined') {
                flashAlert("Situation matrimoniale", "success",
                    `La situation matrimoniale de l'${typePersonne} a été mise à jour. Vous pouvez maintenant continuer.`);
            }

        } else if (situationMatrimoniale === 'SMAT_0003' || situationMatrimoniale === 'SMAT_0004') {
            // Marié(e) - maintenir le blocage
            notificationDiv.innerHTML = `
                <div class="alert alert-warning">
                    <h6><i class="fa fa-exclamation-triangle"></i> Situation matrimoniale incompatible</h6>
                    <p>La situation "Marié(e)" n'est pas compatible avec un nouveau mariage.</p>
                    <p>Veuillez fournir les documents justificatifs (jugement de divorce ou acte de décès).</p>
                </div>
            `;

            bloquerAvancement();

            // Afficher une notification flash
            if (typeof flashAlert !== 'undefined') {
                flashAlert("Situation matrimoniale", "warning",
                    `La situation "Marié(e)" nécessite des documents justificatifs pour un nouveau mariage.`);
            }
        }
    }
}

// Fonction pour obtenir le libellé d'une situation matrimoniale
function getLibelleSituationMatrimoniale(code) {
    const situations = {
        'SMAT_0001': 'Célibataire',
        'SMAT_0002': 'Divorcé(e)',
        'SMAT_0003': 'Marié(e)',
        'SMAT_0004': 'Marié(e)',
        'SMAT_0005': 'Veuf/Veuve',
        'SMAT_0006': 'Autre'
    };
    return situations[code] || 'Inconnue';
}

// Fonction pour vérifier le changement du numéro d'acte de mariage
function verifierChangementActeMariage(typePersonne) {
    const numeroActe = document.getElementById(`numero_acte_mariage_${typePersonne}`).value;
    const notificationDiv = document.getElementById(`notification_acte_mariage_${typePersonne}`);

    console.log(`Changement du numéro d'acte de mariage pour ${typePersonne}:`, numeroActe);

    // Si le formulaire est bloqué et que l'utilisateur vide le champ
    if (document.body.classList.contains('mariage-bloque') && (!numeroActe || numeroActe.trim() === '')) {

        // Champ vidé - débloquer
        notificationDiv.innerHTML = `
            <div class="alert alert-info">
                <h6><i class="fa fa-info-circle"></i> Champ d'acte de mariage vidé</h6>
                <p>Le numéro d'acte de mariage a été supprimé. Le processus peut maintenant continuer.</p>
                <p>Assurez-vous que la situation matrimoniale correspond à la réalité.</p>
            </div>
        `;

        permettreAvancement();

        // Afficher une notification flash
        if (typeof flashAlert !== 'undefined') {
            flashAlert("Acte de mariage", "info",
                `Le numéro d'acte de mariage de l'${typePersonne} a été supprimé. Vous pouvez maintenant continuer.`);
        }
    }
}

// Fonction pour détecter et configurer le bouton suivant du wizard
function configurerBoutonSuivant() {
    // Attendre que le DOM soit chargé
    setTimeout(() => {
        const selectors = [
            '.wizard .actions ul li.next a',
            '.validation-wizard .actions ul li.next a',
            '.wizard-circle .actions ul li.next a',
            '.actions ul li.next a',
            'a[href="#next"]',
            'button[type="submit"]',
            '.btn-next',
            '.wizard-next'
        ];

        let nextButton = null;
        for (const selector of selectors) {
            nextButton = document.querySelector(selector);
            if (nextButton) {
                console.log('Bouton suivant détecté:', selector, nextButton);

                // Ajouter un événement pour empêcher le clic si bloqué
                nextButton.addEventListener('click', function(e) {
                    if (document.body.classList.contains('mariage-bloque')) {
                        e.preventDefault();
                        e.stopPropagation();

                        // Afficher un message d'erreur
                        if (typeof flashAlert !== 'undefined') {
                            flashAlert("Avancement bloqué", "error", "Vous ne pouvez pas continuer tant que le problème avec l'acte de mariage n'est pas résolu.");
                        }

                        return false;
                    }
                });

                break;
            }
        }

        if (!nextButton) {
            console.warn('Aucun bouton suivant trouvé pour la configuration');
        }
    }, 1000);
}

// Fonction de test pour vérifier la connectivité
function testerConnexion() {
    console.log('Test de connexion à la route de vérification...');
    fetch('/acteMariage/acte/search/TEST123', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Statut de la réponse:', response.status);
        console.log('Headers de la réponse:', response.headers);
        return response.text();
    })
    .then(text => {
        console.log('Réponse brute:', text);
        try {
            const data = JSON.parse(text);
            console.log('Données JSON:', data);
        } catch (e) {
            console.log('Réponse non-JSON:', text);
        }
    })
    .catch(error => {
        console.error('Erreur de test:', error);
    });
}

// Fonction pour afficher la situation matrimoniale lors de la recherche
function afficherSituationMatrimoniale(typePersonne, situationData) {
    const notificationDiv = document.getElementById(`notification_situation_${typePersonne}`);
    const situationDiv = document.getElementById(`situation_matrimoniale_${typePersonne}`);

    if (!situationData || !notificationDiv || !situationDiv) {
        return;
    }

    let alertClass = 'alert-info';
    let icon = 'fa-info-circle';
    let title = 'Situation matrimoniale';

    switch(situationData.statut) {
        case 'celibataire':
            alertClass = 'alert-success';
            icon = 'fa-check-circle';
            title = 'Personne célibataire';
            break;
        case 'marie_monogamie':
            alertClass = 'alert-warning';
            icon = 'fa-exclamation-triangle';
            title = 'Personne déjà mariée (Monogamie)';
            break;
        case 'polygame':
            alertClass = 'alert-info';
            icon = 'fa-info-circle';
            title = 'Personne polygame';
            break;
        case 'decede':
            alertClass = 'alert-danger';
            icon = 'fa-times-circle';
            title = 'Personne décédée';
            break;
        case 'erreur':
            alertClass = 'alert-danger';
            icon = 'fa-exclamation-circle';
            title = 'Erreur de vérification';
            break;
        default:
            alertClass = 'alert-warning';
            icon = 'fa-question-circle';
            title = 'Situation matrimoniale';
            break;
    }

    let actesHtml = '';
    if (situationData.actes && situationData.actes.length > 0) {
        actesHtml = `
            <div class="mt-2">
                <strong>Actes de mariage trouvés :</strong>
                <ul class="mb-0">
                    ${situationData.actes.map(acte => `
                        <li>
                            Code: ${acte.code_acte} |
                            Date: ${new Date(acte.date_emission).toLocaleDateString('fr-FR')} |
                            Option: ${acte.option_mariage}
                        </li>
                    `).join('')}
                </ul>
            </div>
        `;
    }

    let conjointInfo = '';
    if (situationData.conjoint) {
        conjointInfo = `<br><strong>Conjoint(e) :</strong> ${situationData.conjoint}`;
    }

    notificationDiv.innerHTML = `
        <div class="alert ${alertClass}">
            <h6><i class="fa ${icon}"></i> ${title}</h6>
            <p>${situationData.message}${conjointInfo}</p>
            ${actesHtml}
        </div>
    `;

    situationDiv.style.display = 'block';

    // Appliquer les conditions selon la situation
    appliquerConditionsMatrimoniales(typePersonne, situationData.statut);
}

// Fonction pour appliquer les conditions selon la situation matrimoniale
function appliquerConditionsMatrimoniales(typePersonne, statut) {
    switch(statut) {
        case 'marie_monogamie':
            // Bloquer l'avancement si la personne est mariée en monogamie
            bloquerAvancement();

            // Afficher une notification flash
            if (typeof flashAlert !== 'undefined') {
                flashAlert("Situation matrimoniale", "warning",
                    `La personne est déjà mariée en monogamie. Veuillez fournir les documents justificatifs (divorce ou décès).`);
            }
            break;

        case 'polygame':
        case 'celibataire':
            // Permettre l'avancement
            permettreAvancement();

            // Afficher une notification flash
            if (typeof flashAlert !== 'undefined') {
                const message = statut === 'polygame'
                    ? 'La personne est polygame. Le processus peut continuer.'
                    : 'La personne est célibataire. Le processus peut continuer.';
                flashAlert("Situation matrimoniale", "success", message);
            }
            break;

        case 'erreur':
            // En cas d'erreur, permettre l'avancement mais afficher un avertissement
            permettreAvancement();

            if (typeof flashAlert !== 'undefined') {
                flashAlert("Situation matrimoniale", "warning",
                    "Erreur lors de la vérification. Veuillez vérifier manuellement la situation matrimoniale.");
            }
            break;
    }
}

// Fonction pour rechercher une personne avec vérification de la situation matrimoniale
function rechercherPersonneAvecVerification(numeroActe, typePersonne) {
    if (!numeroActe || numeroActe.trim() === '') {
        return;
    }

    // Afficher un indicateur de chargement
    const notificationDiv = document.getElementById(`notification_situation_${typePersonne}`);
    if (notificationDiv) {
        notificationDiv.innerHTML = '<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Vérification de la situation matrimoniale...</div>';
    }

    // Vérifier la présence du token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        if (notificationDiv) {
            notificationDiv.innerHTML = `
                <div class="alert alert-danger">
                    <h6><i class="fa fa-exclamation-circle"></i> Erreur de configuration</h6>
                    <p>Token CSRF manquant. Veuillez recharger la page.</p>
                </div>
            `;
        }
        return;
    }

    // Requête Ajax pour rechercher la personne avec vérification matrimoniale
    fetch(`/declarationMariage/recherchePersonne`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            numero_acte_naissance: numeroActe,
            type_personne: typePersonne
        })
    })
    .then(async response => {
        let data;
        try {
            // Lire le JSON même en cas d'erreur HTTP pour récupérer le message
            const text = await response.text();
            try {
                data = JSON.parse(text);
            } catch (parseError) {
                // Si le parsing JSON échoue, c'est une vraie erreur de connexion
                throw new Error('Erreur lors du traitement de la réponse du serveur');
            }
        } catch (error) {
            // Si la lecture de la réponse échoue complètement
            throw error;
        }

        // Vérifier si c'est une erreur d'âge (code 400 avec informations d'âge)
        // Vérifier à la fois le code string "400" et le code numérique 400
        const isAgeError = data && (data.code === "400" || data.code === 400) && (data.age_actuel !== undefined || data.age_actuel === 0);

        if (isAgeError) {
            // Construire le message d'erreur d'âge
            const messageErreur = data.message || `L'âge minimum requis pour un(e) ${typePersonne === 'epoux' ? 'époux' : 'épouse'} est de ${data.age_minimum || (typePersonne === 'epoux' ? 21 : 18)} ans. L'âge actuel est de ${data.age_actuel} an(s).`;

            const resultatDiv = document.getElementById(`resultat_recherche_${typePersonne}`);
            if (resultatDiv) {
                resultatDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <h6><i class="fa fa-exclamation-triangle"></i> Âge insuffisant</h6>
                        <p><strong>${messageErreur}</strong></p>
                        <p><small>Âge actuel : <strong>${data.age_actuel} an(s)</strong></p>
                        <p><small>Âge minimum requis : <strong>${data.age_minimum || (typePersonne === 'epoux' ? 21 : 18)} ans</strong></small></p>
                    </div>
                `;
            }

            // Afficher aussi dans la zone de notification
            const notificationDiv = document.getElementById(`notification_situation_${typePersonne}`);
            if (notificationDiv) {
                notificationDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <h6><i class="fa fa-exclamation-triangle"></i> Âge insuffisant</h6>
                        <p><strong>${messageErreur}</strong></p>
                    </div>
                `;
            }

            // Afficher notification flash avec le message d'erreur d'âge - PRIORITÉ ABSOLUE
            if (typeof flashAlert !== 'undefined') {
                flashAlert("Âge insuffisant", "error", messageErreur);
            } else if (typeof toastr !== 'undefined') {
                toastr.error(messageErreur, "Âge insuffisant");
            } else {
                alert("❌ ÂGE INSUFFISANT\n\n" + messageErreur);
            }

            // Arrêter l'exécution pour éviter d'aller dans le catch
            return;
        }

        if (data.code === "200") {
            // Vérifier le sexe selon le type de personne
            const sexeValide = validerSexePersonne(typePersonne, data.sexe);

            if (!sexeValide.valide) {
                // Sexe invalide, afficher erreur dans la modal
                const resultatDiv = document.getElementById(`resultat_recherche_${typePersonne}`);
                if (resultatDiv) {
                    resultatDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <h6><i class="fa fa-exclamation-circle"></i> Erreur de sélection</h6>
                            <p>${sexeValide.message}</p>
                            <div class="mt-3">
                                <h6>Personne trouvée :</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Nom et Prénom :</strong><br>
                                        ${data.nom} ${data.prenom}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Sexe :</strong><br>
                                        <span class="text-danger">${data.sexe === 'M' ? 'Masculin' : 'Féminin'}</span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>Date de naissance :</strong><br>
                                        ${data.date_naissance ? new Date(data.date_naissance).toLocaleDateString('fr-FR') : 'Non spécifiée'}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Lieu de naissance :</strong><br>
                                        ${data.lieu_naissance || 'Non spécifié'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }

                // Afficher notification flash
                if (typeof flashAlert !== 'undefined') {
                    flashAlert("Erreur de sélection", "error", sexeValide.message);
                }

                return;
            }


            // Personne trouvée et sexe valide, afficher les détails avec bouton de confirmation
            const resultatDiv = document.getElementById(`resultat_recherche_${typePersonne}`);
            if (resultatDiv) {

                let situationMatrimonialeHtml = '';
                if (data.situation_matrimoniale) {
                    let alertClass = 'alert-info';
                    let icon = 'fa-info-circle';

                    // Déterminer la classe d'alerte et l'icône selon le statut
                    switch(data.situation_matrimoniale.statut) {
                        case 'celibataire':
                            alertClass = 'alert-success';
                            icon = 'fa-check-circle';
                            break;
                        case 'marie_monogamie':
                            alertClass = 'alert-warning';
                            icon = 'fa-exclamation-triangle';
                            break;
                        case 'polygame':
                            alertClass = 'alert-info';
                            icon = 'fa-info-circle';
                            break;
                        case 'erreur':
                            alertClass = 'alert-danger';
                            icon = 'fa-exclamation-circle';
                            break;
                        case 'decede':
                            alertClass = 'alert-danger';
                            icon = 'fa-times-circle';
                            break;
                    }

                    let conjointInfo = '';
                    if (data.situation_matrimoniale.conjoint) {
                        conjointInfo = `<br><strong>Conjoint(e) :</strong> ${data.situation_matrimoniale.conjoint}`;
                    }

                    situationMatrimonialeHtml = `
                        <div class="mt-3">
                            <h6><i class="fa fa-heart"></i> Situation matrimoniale :</h6>
                            <div class="alert ${alertClass}">
                                <i class="fa ${icon}"></i> <strong>${data.situation_matrimoniale.message}</strong>${conjointInfo}
                            </div>
                        </div>
                    `;

                }

                resultatDiv.innerHTML = `
                    <div class="alert alert-success">
                        <h6><i class="fa fa-user-check"></i> Identité trouvée</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Nom et Prénom :</strong><br>
                                ${data.nom} ${data.prenom}
                            </div>
                            <div class="col-md-6">
                                <strong>Sexe :</strong><br>
                                <span class="text-success">${data.sexe === 'M' ? 'Masculin' : 'Féminin'}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Date de naissance :</strong><br>
                                ${data.date_naissance ? new Date(data.date_naissance).toLocaleDateString('fr-FR') : 'Non spécifiée'}
                            </div>
                            <div class="col-md-6">
                                <strong>Lieu de naissance :</strong><br>
                                ${data.lieu_naissance || 'Non spécifié'}
                            </div>
                        </div>
                        ${situationMatrimonialeHtml}
                        <div class="mt-3">
                            ${data.situation_matrimoniale && (data.situation_matrimoniale.statut === 'marie_monogamie' || data.situation_matrimoniale.statut === 'decede')
                                ? `<button type="button" class="btn btn-danger" disabled>
                                    <i class="fa fa-ban"></i> Confirmation impossible
                                   </button>
                                   <p class="text-danger mt-2"><small>${data.situation_matrimoniale.message}</small></p>`
                                : `<button type="button" class="btn btn-success" onclick="confirmer${typePersonne.charAt(0).toUpperCase() + typePersonne.slice(1)}(${JSON.stringify({
                                    nom: data.nom,
                                    prenom: data.prenom,
                                    date_naissance: data.date_naissance,
                                    lieu_naissance: data.lieu_naissance,
                                    numero_acte_naissance: data.numero_acte_naissance,
                                    sexe: data.sexe,
                                    situation_matrimoniale: data.situation_matrimoniale
                                })})">
                                    <i class="fa fa-check"></i> Confirmer et remplir le formulaire
                                </button>`
                            }
                        </div>
                    </div>
                `;

            }

            // Afficher aussi la situation matrimoniale dans la zone principale si elle existe
            if (data.situation_matrimoniale) {
                afficherSituationMatrimoniale(typePersonne, data.situation_matrimoniale);
            }
        } else {

            // Erreur de recherche
            const resultatDiv = document.getElementById(`resultat_recherche_${typePersonne}`);
            if (resultatDiv) {
                resultatDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <h6><i class="fa fa-exclamation-circle"></i> Erreur de recherche</h6>
                        <p>${data.message}</p>
                    </div>
                `;
            }
        } else if (data && data.code) {
            // Autre erreur (code différent de 200 et pas d'erreur d'âge)
            const resultatDiv = document.getElementById(`resultat_recherche_${typePersonne}`);
            if (resultatDiv) {
                resultatDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <h6><i class="fa fa-exclamation-circle"></i> Erreur de recherche</h6>
                        <p><strong>${data.message || 'Une erreur s\'est produite lors de la recherche.'}</strong></p>
                    </div>
                `;
            }

            if (typeof flashAlert !== 'undefined') {
                flashAlert("Erreur", "error", data.message || 'Une erreur s\'est produite lors de la recherche.');
            }
        }
    })
    .catch(error => {
        console.error('Erreur lors de la recherche:', error);

        // Ne pas afficher d'erreur si c'est une erreur d'âge (déjà gérée)
        if (error.message && error.message.includes('âge')) {
            return;
        }

        const resultatDiv = document.getElementById(`resultat_recherche_${typePersonne}`);
        if (resultatDiv) {
            // Vérifier si c'est une erreur réseau ou une erreur de parsing
            let messageErreur = 'Une erreur s\'est produite lors de la recherche. Veuillez réessayer.';
            let titreErreur = 'Erreur de recherche';

            if (error.message && error.message.includes('HTTP')) {
                messageErreur = 'Erreur de connexion au serveur. Veuillez vérifier votre connexion et réessayer.';
                titreErreur = 'Erreur de connexion';
            } else if (error.message && error.message.includes('JSON') || error.message.includes('traitement')) {
                messageErreur = 'Erreur lors du traitement de la réponse. Veuillez réessayer.';
                titreErreur = 'Erreur de traitement';
            }

            resultatDiv.innerHTML = `
                <div class="alert alert-danger">
                    <h6><i class="fa fa-exclamation-circle"></i> ${titreErreur}</h6>
                    <p>${messageErreur}</p>
                    <p><small><strong>Détails techniques :</strong> ${error.message}</small></p>
                </div>
            `;
        }

        // Afficher notification flash seulement pour les vraies erreurs de connexion
        if (typeof flashAlert !== 'undefined') {
            flashAlert("Erreur", "error", messageErreur);
        }
    });
}

// Fonction pour valider le sexe selon le type de personne
function validerSexePersonne(typePersonne, sexe) {
    if (typePersonne === 'epoux') {
        if (sexe === 'F') {
            return {
                valide: false,
                message: 'Une femme ne peut pas être sélectionnée comme époux. Veuillez rechercher un homme.'
            };
        } else if (sexe === 'M') {
            return {
                valide: true,
                message: 'Sexe valide pour un époux.'
            };
        } else {
            return {
                valide: false,
                message: 'Le sexe de la personne n\'est pas spécifié. Veuillez vérifier les informations.'
            };
        }
    } else if (typePersonne === 'epouse') {
        if (sexe === 'M') {
            return {
                valide: false,
                message: 'Un homme ne peut pas être sélectionné comme épouse. Veuillez rechercher une femme.'
            };
        } else if (sexe === 'F') {
            return {
                valide: true,
                message: 'Sexe valide pour une épouse.'
            };
        } else {
            return {
                valide: false,
                message: 'Le sexe de la personne n\'est pas spécifié. Veuillez vérifier les informations.'
            };
        }
    }

    // Type de personne non reconnu
    return {
        valide: false,
        message: 'Type de personne non reconnu.'
    };
}

// Fonction pour valider le sexe des témoins
function validerSexeTemoin(type, numero, sexe) {
    // Définir le sexe attendu selon le type et le numéro du témoin
    let sexeAttendu = '';
    let typeTemoin = '';

    if (type === 'epoux') {
        if (numero === 1) {
            sexeAttendu = 'M';
            typeTemoin = 'Témoin 1 Époux (Mari)';
        } else if (numero === 2) {
            sexeAttendu = 'F';
            typeTemoin = 'Témoin 2 Époux (Femme)';
        }
    } else if (type === 'epouse') {
        if (numero === 1) {
            sexeAttendu = 'M';
            typeTemoin = 'Témoin 1 Épouse (Mari)';
        } else if (numero === 2) {
            sexeAttendu = 'F';
            typeTemoin = 'Témoin 2 Épouse (Femme)';
        }
    }

    if (!sexeAttendu) {
        return {
            valide: false,
            message: 'Type de témoin non reconnu.'
        };
    }

    if (sexe === sexeAttendu) {
        return {
            valide: true,
            message: `Sexe valide pour ${typeTemoin}.`
        };
    } else {
        const sexeAttenduLibelle = sexeAttendu === 'M' ? 'Masculin' : 'Féminin';
        const sexeActuelLibelle = sexe === 'M' ? 'Masculin' : (sexe === 'F' ? 'Féminin' : 'Non spécifié');

        return {
            valide: false,
            message: `Le ${typeTemoin} doit être de sexe ${sexeAttenduLibelle}. La personne sélectionnée est de sexe ${sexeActuelLibelle}.`
        };
    }
}

// Fonction pour valider la date d'édition de l'acte de naissance
function validerDateActeNaissance(typePersonne) {
    const dateNaissance = document.getElementById(`date_naissance_${typePersonne}`).value;
    const dateEmissionActe = document.getElementById(`date_emission_acte_naissance_${typePersonne}`).value;
    const notificationDiv = document.getElementById(`notificationDateActe${typePersonne.charAt(0).toUpperCase() + typePersonne.slice(1)}`);

    if (dateNaissance && dateEmissionActe) {
        const dateNaissanceObj = new Date(dateNaissance);
        const dateEmissionObj = new Date(dateEmissionActe);

        // Vérifier que la date d'émission n'est pas antérieure à la date de naissance
        if (dateEmissionObj < dateNaissanceObj) {
            notificationDiv.style.display = 'block';
            notificationDiv.textContent = 'La date d\'édition de l\'acte ne peut pas être antérieure à la date de naissance.';

            // Empêcher l'avancement
            bloquerAvancement();

            // Afficher notification flash
            if (typeof flashAlert !== 'undefined') {
                flashAlert("Erreur de date", "error", "La date d'édition de l'acte ne peut pas être antérieure à la date de naissance.");
            }

            return false;
        } else {
            // Date valide, masquer l'erreur
            notificationDiv.style.display = 'none';

            // Permettre l'avancement si pas d'autres erreurs
            permettreAvancement();

            return true;
        }
    }

    return true;
}

// Fonctions pour la recherche des époux/épouses

// Fonction pour rechercher un époux
function rechercherEpoux() {
    const numeroActe = document.getElementById('numero_acte_recherche_epoux').value.trim();

    if (!numeroActe) {
        if (typeof flashAlert !== 'undefined') {
            flashAlert("Erreur", "error", "Veuillez saisir le numéro d'acte de naissance.");
        }
        return;
    }

    // Afficher le loading
    const resultatDiv = document.getElementById('resultat_recherche_epoux');

    resultatDiv.innerHTML = `
        <div class="alert alert-info">
            <i class="fa fa-spinner fa-spin"></i> Recherche en cours...
        </div>
    `;


    // Effectuer la recherche avec validation du sexe
    rechercherPersonneAvecVerification(numeroActe, 'epoux');
}

// Fonction pour rechercher une épouse
function rechercherEpouse() {
    const numeroActe = document.getElementById('numero_acte_recherche_epouse').value.trim();

    if (!numeroActe) {
        if (typeof flashAlert !== 'undefined') {
            flashAlert("Erreur", "error", "Veuillez saisir le numéro d'acte de naissance.");
        }
        return;
    }

    // Afficher le loading
    const resultatDiv = document.getElementById('resultat_recherche_epouse');
    resultatDiv.innerHTML = `
        <div class="alert alert-info">
            <i class="fa fa-spinner fa-spin"></i> Recherche en cours...
        </div>
    `;

    // Effectuer la recherche avec validation du sexe
    rechercherPersonneAvecVerification(numeroActe, 'epouse');
}

// Fonction pour vider la recherche époux
function viderRechercheEpoux() {
    document.getElementById('numero_acte_recherche_epoux').value = '';
    document.getElementById('resultat_recherche_epoux').innerHTML = '';
}

// Fonction pour vider la recherche épouse
function viderRechercheEpouse() {
    document.getElementById('numero_acte_recherche_epouse').value = '';
    document.getElementById('resultat_recherche_epouse').innerHTML = '';
}

// Fonction pour confirmer et remplir le formulaire époux
function confirmerEpoux(personne) {
    // Vérifier la situation matrimoniale avant de remplir
    if (personne.situation_matrimoniale) {
        const statut = personne.situation_matrimoniale.statut;

        // Si la personne est mariée en monogamie, empêcher le remplissage
        if (statut === 'marie_monogamie') {
            const message = personne.situation_matrimoniale.message ||
                'La personne est déjà mariée en monogamie. Un nouveau mariage nécessite un divorce ou un décès de l\'époux/épouse actuel(le).';

            if (typeof flashAlert !== 'undefined') {
                flashAlert("Mariage impossible", "error", message);
            } else {
                alert('❌ MARIAGE IMPOSSIBLE\n\n' + message);
            }

            // Ne pas remplir le formulaire
            return false;
        }

        // Si la personne est décédée, empêcher aussi
        if (statut === 'decede') {
            const message = personne.situation_matrimoniale.message ||
                'Cette personne est décédée. Un mariage posthume n\'est pas autorisé.';

            if (typeof flashAlert !== 'undefined') {
                flashAlert("Mariage impossible", "error", message);
            } else {
                alert('❌ MARIAGE IMPOSSIBLE\n\n' + message);
            }

            return false;
        }
    }

    // Remplir les champs du formulaire principal
    const nomField = document.getElementById('nom_epoux');
    const prenomField = document.getElementById('prenom_epoux');
    const dateNaissanceField = document.getElementById('date_naissance_epoux');
    const lieuNaissanceField = document.getElementById('lieu_naissance_epoux');
    const numeroActeField = document.getElementById('numero_acte_naissance_epoux');
    const situationMatrimonialeField = document.getElementById('sit_matrimoniale_epoux');

    nomField.value = personne.nom || '';
    prenomField.value = personne.prenom || '';
    dateNaissanceField.value = personne.date_naissance || '';
    lieuNaissanceField.value = personne.lib_lieu_naissance || personne.lieu_naissance || '';
    numeroActeField.value = personne.numero_acte_naissance || '';

    // Remplir automatiquement la situation matrimoniale si disponible
    if (personne.situation_matrimoniale && situationMatrimonialeField) {
        const situationCode = determinerCodeSituationMatrimoniale(personne.situation_matrimoniale);
        if (situationCode) {
            situationMatrimonialeField.value = situationCode;
            // Déclencher l'événement de changement pour activer la logique conditionnelle
            situationMatrimonialeField.dispatchEvent(new Event('change'));
        }
    }

    // Ajouter le mode lecture simple (griser les champs remplis)
    if (personne.nom) {
        nomField.classList.add('form-control-readonly');
        nomField.style.backgroundColor = '#f8f9fa';
        nomField.style.color = '#6c757d';
        nomField.readOnly = true;
    }
    if (personne.prenom) {
        prenomField.classList.add('form-control-readonly');
        prenomField.style.backgroundColor = '#f8f9fa';
        prenomField.style.color = '#6c757d';
        prenomField.readOnly = true;
    }
    if (personne.date_naissance) {
        dateNaissanceField.classList.add('form-control-readonly');
        dateNaissanceField.style.backgroundColor = '#f8f9fa';
        dateNaissanceField.style.color = '#6c757d';
        dateNaissanceField.readOnly = true;
    }
    if (personne.lieu_naissance) {
        lieuNaissanceField.classList.add('form-control-readonly');
        lieuNaissanceField.style.backgroundColor = '#f8f9fa';
        lieuNaissanceField.style.color = '#6c757d';
        lieuNaissanceField.readOnly = true;
    }
    if (personne.numero_acte_naissance) {
        numeroActeField.classList.add('form-control-readonly');
        numeroActeField.style.backgroundColor = '#f8f9fa';
        numeroActeField.style.color = '#6c757d';
        numeroActeField.readOnly = true;
    }

    // Afficher l'identité trouvée
    document.getElementById('nom_prenom_epoux_trouve').textContent = `${personne.nom} ${personne.prenom}`;
    document.getElementById('date_naissance_epoux_trouve').textContent = personne.date_naissance ? new Date(personne.date_naissance).toLocaleDateString('fr-FR') : '';
    document.getElementById('numero_acte_epoux_trouve').textContent = personne.numero_acte_naissance || '';
    document.getElementById('identite_trouvee_epoux').style.display = 'block';

    // Fermer la modal
    const modal = bootstrap.Modal.getInstance(document.querySelector('.epoux-search-modal-lg'));
    if (modal) {
        modal.hide();
    }

    // Afficher notification de succès
    if (typeof flashAlert !== 'undefined') {
        flashAlert("Succès", "success", "Époux sélectionné avec succès.");
    }
}

// Fonction pour confirmer et remplir le formulaire épouse
function confirmerEpouse(personne) {
    // Vérifier la situation matrimoniale avant de remplir
    if (personne.situation_matrimoniale) {
        const statut = personne.situation_matrimoniale.statut;

        // Si la personne est mariée en monogamie, empêcher le remplissage
        if (statut === 'marie_monogamie') {
            const message = personne.situation_matrimoniale.message ||
                'La personne est déjà mariée en monogamie. Un nouveau mariage nécessite un divorce ou un décès de l\'époux/épouse actuel(le).';

            if (typeof flashAlert !== 'undefined') {
                flashAlert("Mariage impossible", "error", message);
            } else {
                alert('❌ MARIAGE IMPOSSIBLE\n\n' + message);
            }

            // Ne pas remplir le formulaire
            return false;
        }

        // Si la personne est décédée, empêcher aussi
        if (statut === 'decede') {
            const message = personne.situation_matrimoniale.message ||
                'Cette personne est décédée. Un mariage posthume n\'est pas autorisé.';

            if (typeof flashAlert !== 'undefined') {
                flashAlert("Mariage impossible", "error", message);
            } else {
                alert('❌ MARIAGE IMPOSSIBLE\n\n' + message);
            }

            return false;
        }
    }

    // Remplir les champs du formulaire principal
    const nomField = document.getElementById('nom_epouse');
    const prenomField = document.getElementById('prenom_epouse');
    const dateNaissanceField = document.getElementById('date_naissance_epouse');
    const lieuNaissanceField = document.getElementById('lieu_naissance_epouse');
    const numeroActeField = document.getElementById('numero_acte_naissance_epouse');

    nomField.value = personne.nom || '';
    prenomField.value = personne.prenom || '';
    dateNaissanceField.value = personne.date_naissance || '';
    lieuNaissanceField.value = personne.lib_lieu_naissance || personne.lieu_naissance || '';
    numeroActeField.value = personne.numero_acte_naissance || '';

    // Ajouter le mode lecture simple (griser les champs remplis)
    if (personne.nom) {
        nomField.classList.add('form-control-readonly');
        nomField.style.backgroundColor = '#f8f9fa';
        nomField.style.color = '#6c757d';
        nomField.readOnly = true;
    }
    if (personne.prenom) {
        prenomField.classList.add('form-control-readonly');
        prenomField.style.backgroundColor = '#f8f9fa';
        prenomField.style.color = '#6c757d';
        prenomField.readOnly = true;
    }
    if (personne.date_naissance) {
        dateNaissanceField.classList.add('form-control-readonly');
        dateNaissanceField.style.backgroundColor = '#f8f9fa';
        dateNaissanceField.style.color = '#6c757d';
        dateNaissanceField.readOnly = true;
    }
    if (personne.lieu_naissance) {
        lieuNaissanceField.classList.add('form-control-readonly');
        lieuNaissanceField.style.backgroundColor = '#f8f9fa';
        lieuNaissanceField.style.color = '#6c757d';
        lieuNaissanceField.readOnly = true;
    }
    if (personne.numero_acte_naissance) {
        numeroActeField.classList.add('form-control-readonly');
        numeroActeField.style.backgroundColor = '#f8f9fa';
        numeroActeField.style.color = '#6c757d';
        numeroActeField.readOnly = true;
    }

    // Remplir automatiquement la situation matrimoniale si disponible
    const situationMatrimonialeField = document.getElementById('sit_matrimoniale_epouse');
    if (personne.situation_matrimoniale && situationMatrimonialeField) {
        const situationCode = determinerCodeSituationMatrimoniale(personne.situation_matrimoniale);
        if (situationCode) {
            situationMatrimonialeField.value = situationCode;
            // Déclencher l'événement de changement pour activer la logique conditionnelle
            situationMatrimonialeField.dispatchEvent(new Event('change'));
        }
    }

    // Afficher l'identité trouvée
    document.getElementById('nom_prenom_epouse_trouve').textContent = `${personne.nom} ${personne.prenom}`;
    document.getElementById('date_naissance_epouse_trouve').textContent = personne.date_naissance ? new Date(personne.date_naissance).toLocaleDateString('fr-FR') : '';
    document.getElementById('numero_acte_epouse_trouve').textContent = personne.numero_acte_naissance || '';
    document.getElementById('identite_trouvee_epouse').style.display = 'block';

    // Fermer la modal
    const modal = bootstrap.Modal.getInstance(document.querySelector('.epouse-search-modal-lg'));
    if (modal) {
        modal.hide();
    }

    // Afficher notification de succès
    if (typeof flashAlert !== 'undefined') {
        flashAlert("Succès", "success", "Épouse sélectionnée avec succès.");
    }
}

// Fonctions pour la recherche des témoins

// Fonction pour rechercher un témoin
function rechercherTemoin(type, numero) {
    console.log(`Recherche témoin ${type}_${numero} démarrée`);

    const nom = document.getElementById(`nom_recherche_temoin_${type}_${numero}`).value;
    const prenom = document.getElementById(`prenom_recherche_temoin_${type}_${numero}`).value;
    const sexe = document.getElementById(`sexe_recherche_temoin_${type}_${numero}`).value;
    const dateNaissance = document.getElementById(`date_naissance_recherche_temoin_${type}_${numero}`).value;
    const lieuNaissance = document.getElementById(`lieu_recherche_temoin_${type}_${numero}`).value;

    if (!nom || nom.trim() === '') {
        if (typeof flashAlert !== 'undefined') {
            flashAlert("Recherche de témoin", "error", "Le nom est obligatoire pour la recherche.");
        }
        return;
    }

    // Afficher un indicateur de chargement
    const resultatsDiv = document.getElementById(`resultats_recherche_temoin_${type}_${numero}`);
    resultatsDiv.innerHTML = '<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Recherche en cours...</div>';
    resultatsDiv.style.display = 'block';

    // Vérifier la présence du token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        resultatsDiv.innerHTML = `
            <div class="alert alert-danger">
                <h6><i class="fa fa-exclamation-circle"></i> Erreur de configuration</h6>
                <p>Token CSRF manquant. Veuillez recharger la page.</p>
            </div>
        `;
        return;
    }

    // Requête Ajax pour rechercher le témoin
    fetch(`/declarationMariage/rechercheTemoin`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            nom: nom,
            prenom: prenom,
            sexe: sexe,
            date_naissance: dateNaissance,
            lieu_naissance: lieuNaissance
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status} - ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.code === "200" && data.resultats && data.resultats.length > 0) {
            afficherResultatsTemoin(type, numero, data.resultats);
        } else {
            resultatsDiv.innerHTML = `
                <div class="alert alert-warning">
                    <h6><i class="fa fa-info-circle"></i> Aucun résultat trouvé</h6>
                    <p>Aucun témoin trouvé avec ces critères de recherche.</p>
                    <p><strong>Note :</strong> Seules les personnes âgées de plus de 18 ans peuvent être témoins.</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Erreur lors de la recherche du témoin:', error);
        resultatsDiv.innerHTML = `
            <div class="alert alert-danger">
                <h6><i class="fa fa-exclamation-circle"></i> Erreur de recherche</h6>
                <p>Une erreur s'est produite lors de la recherche. Veuillez réessayer.</p>
                <p><strong>Détails :</strong> ${error.message}</p>
            </div>
        `;
    });
}

// Fonction pour afficher les résultats de recherche des témoins
function afficherResultatsTemoin(type, numero, resultats) {
    const resultatsDiv = document.getElementById(`resultats_recherche_temoin_${type}_${numero}`);

    let html = `
        <div class="alert alert-success">
            <h6><i class="fa fa-check-circle"></i> Résultats de recherche</h6>
            <p>${resultats.length} témoin(s) trouvé(s) :</p>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Sexe</th>
                        <th>Date de naissance</th>
                        <th>Lieu de naissance</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
    `;

    resultats.forEach((temoin, index) => {
        // Debug: afficher les données du témoin
        console.log(`Témoin ${index} pour ${type}_${numero}:`, temoin);

        // Vérifier si le sexe est valide pour ce type de témoin
        const sexeValide = validerSexeTemoin(type, numero, temoin.sexe);
        const sexeClass = sexeValide.valide ? 'text-success' : 'text-danger';
        const sexeIcon = sexeValide.valide ? 'fa-check-circle' : 'fa-times-circle';
        const buttonClass = sexeValide.valide ? 'btn-success' : 'btn-warning';
        const buttonText = sexeValide.valide ? 'Sélectionner' : 'Sexe incorrect';

        html += `
            <tr>
                <td>${temoin.nom || ''}</td>
                <td>${temoin.prenom || ''}</td>
                <td class="${sexeClass}">
                    <i class="fa ${sexeIcon}"></i>
                    ${temoin.sexe === 'M' ? 'Masculin' : (temoin.sexe === 'F' ? 'Féminin' : 'Non spécifié')}
                </td>
                <td>${temoin.date_naissance ? new Date(temoin.date_naissance).toLocaleDateString('fr-FR') : 'Non spécifiée'}</td>
                <td>${temoin.lib_lieu_naissance || temoin.lieu_naissance || 'Non spécifié'}</td>
                <td>
                    <button type="button" class="btn ${buttonClass} btn-sm" onclick="selectionnerTemoin('${type}', ${numero}, ${index})"
                            ${!sexeValide.valide ? 'title="' + sexeValide.message + '"' : ''}>
                        <i class="fa ${sexeValide.valide ? 'fa-check' : 'fa-exclamation-triangle'}"></i> ${buttonText}
                    </button>
                </td>
            </tr>
        `;
    });

    html += `
                </tbody>
            </table>
        </div>
    `;

    resultatsDiv.innerHTML = html;
    resultatsDiv.style.display = 'block';

    // Stocker les résultats pour la sélection
    window[`resultatsTemoin_${type}_${numero}`] = resultats;
}

// Fonction pour sélectionner un témoin
function selectionnerTemoin(type, numero, index) {
    console.log(`Sélection témoin ${type}_${numero} index ${index}`);

    const resultats = window[`resultatsTemoin_${type}_${numero}`];
    if (!resultats || !resultats[index]) {
        console.log('Aucun résultat trouvé pour la sélection');
        return;
    }

    const temoin = resultats[index];
    console.log('Témoin sélectionné:', temoin);

    // Vérifier le sexe du témoin selon le type et le numéro
    const sexeValide = validerSexeTemoin(type, numero, temoin.sexe);

    if (!sexeValide.valide) {
        // Afficher une notification d'erreur
        if (typeof flashAlert !== 'undefined') {
            flashAlert("Erreur de sélection", "error", sexeValide.message);
        }
        return;
    }

    // Remplir les champs du formulaire
    const nomField = document.getElementById(`nom_t_${type}_${numero}`);
    const prenomField = document.getElementById(`prenom_t_${type}_${numero}`);
    const dateNaissanceField = document.getElementById(`date_naissance_t_${type}_${numero}`);

    nomField.value = temoin.nom || '';
    prenomField.value = temoin.prenom || '';
    dateNaissanceField.value = temoin.date_naissance || '';

    // Remplir le lieu de naissance si disponible
    if (temoin.lieu_naissance || temoin.lib_lieu_naissance) {
        const selectLieu = document.getElementById(`code_localite_t_${type}_${numero}`);
        if (selectLieu) {
            const options = Array.from(selectLieu.options).map(opt => ({value: opt.value, text: opt.text}));

            // Essayer d'abord par le libellé (plus fiable)
            let selectedValue = '';
            if (temoin.lib_lieu_naissance) {
                const optionByText = options.find(opt => opt.text === temoin.lib_lieu_naissance);
                if (optionByText) {
                    selectedValue = optionByText.value;
                    console.log(`Lieu trouvé par libellé: ${optionByText.value} = ${optionByText.text}`);
                }
            }

            // Si pas trouvé par libellé, essayer par le code
            if (!selectedValue && temoin.lieu_naissance) {
                const optionByCode = options.find(opt => opt.value === temoin.lieu_naissance);
                if (optionByCode) {
                    selectedValue = optionByCode.value;
                    console.log(`Lieu trouvé par code: ${optionByCode.value} = ${optionByCode.text}`);
                }
            }

            // Définir la valeur
            if (selectedValue) {
                selectLieu.value = selectedValue;
                console.log(`Lieu de naissance défini: ${selectedValue}`);
            } else {
                console.log(`Aucune option trouvée pour: code="${temoin.lieu_naissance}", libellé="${temoin.lib_lieu_naissance}"`);
                console.log(`Options disponibles:`, options.map(opt => `${opt.value} = ${opt.text}`));

                // Essayer une recherche partielle par libellé
                if (temoin.lib_lieu_naissance) {
                    const partialMatch = options.find(opt =>
                        opt.text.toLowerCase().includes(temoin.lib_lieu_naissance.toLowerCase()) ||
                        temoin.lib_lieu_naissance.toLowerCase().includes(opt.text.toLowerCase())
                    );
                    if (partialMatch) {
                        selectLieu.value = partialMatch.value;
                        console.log(`Correspondance partielle trouvée: ${partialMatch.value} = ${partialMatch.text}`);
                    } else {
                        // Si aucune correspondance, créer une option temporaire
                        const newOption = document.createElement('option');
                        newOption.value = temoin.lieu_naissance;
                        newOption.text = temoin.lib_lieu_naissance;
                        newOption.style.color = 'red'; // Marquer comme temporaire
                        selectLieu.appendChild(newOption);
                        selectLieu.value = temoin.lieu_naissance;
                        console.log(`Option temporaire créée: ${temoin.lieu_naissance} = ${temoin.lib_lieu_naissance}`);

                        // Afficher une notification
                        if (typeof flashAlert !== 'undefined') {
                            flashAlert("Information", "warning", `Le lieu de naissance "${temoin.lib_lieu_naissance}" n'était pas dans la liste. Il a été ajouté temporairement.`);
                        }
                    }
                }
            }
        }
    }

    // Remplir la nationalité si disponible
    if (temoin.code_nationalite) {
        const selectNationalite = document.getElementById(`code_nationalite_t_${type}_${numero}`);
        if (selectNationalite) {
            selectNationalite.value = temoin.code_nationalite;
        }
    }

    // Remplir la profession si disponible
    if (temoin.code_profession) {
        const selectProfession = document.getElementById(`code_profession_t_${type}_${numero}`);
        if (selectProfession) {
            selectProfession.value = temoin.code_profession;
        }
    }

    // Ajouter le mode lecture simple (griser les champs remplis)
    if (temoin.nom) {
        nomField.classList.add('form-control-readonly');
        nomField.style.backgroundColor = '#f8f9fa';
        nomField.style.color = '#6c757d';
        nomField.readOnly = true;
    }
    if (temoin.prenom) {
        prenomField.classList.add('form-control-readonly');
        prenomField.style.backgroundColor = '#f8f9fa';
        prenomField.style.color = '#6c757d';
        prenomField.readOnly = true;
    }
    if (temoin.date_naissance) {
        dateNaissanceField.classList.add('form-control-readonly');
        dateNaissanceField.style.backgroundColor = '#f8f9fa';
        dateNaissanceField.style.color = '#6c757d';
        dateNaissanceField.readOnly = true;
    }
    if (temoin.lieu_naissance) {
        const selectLieu = document.getElementById(`code_localite_t_${type}_${numero}`);
        if (selectLieu) {
            selectLieu.classList.add('form-control-readonly');
            selectLieu.style.backgroundColor = '#f8f9fa';
            selectLieu.style.color = '#6c757d';
            selectLieu.disabled = true;
        }
    }
    if (temoin.code_nationalite) {
        const selectNationalite = document.getElementById(`code_nationalite_t_${type}_${numero}`);
        if (selectNationalite) {
            selectNationalite.classList.add('form-control-readonly');
            selectNationalite.style.backgroundColor = '#f8f9fa';
            selectNationalite.style.color = '#6c757d';
            selectNationalite.disabled = true;
        }
    }
    if (temoin.code_profession) {
        const selectProfession = document.getElementById(`code_profession_t_${type}_${numero}`);
        if (selectProfession) {
            selectProfession.classList.add('form-control-readonly');
            selectProfession.style.backgroundColor = '#f8f9fa';
            selectProfession.style.color = '#6c757d';
            selectProfession.disabled = true;
        }
    }

    // Fermer la modal
    const modal = document.querySelector(`.temoin-${type}-${numero}-search-modal-lg`);
    if (modal) {
        const bootstrapModal = bootstrap.Modal.getInstance(modal);
        if (bootstrapModal) {
            bootstrapModal.hide();
        }
    }

    // Afficher une notification de succès
    if (typeof flashAlert !== 'undefined') {
        flashAlert("Témoin sélectionné", "success", `Le témoin ${temoin.nom} ${temoin.prenom} a été sélectionné avec succès.`);
    }
}

// Fonctions pour vider les champs des témoins
function viderTemoinEpoux1() {
    viderChampsTemoin('epoux', 1);
}

function viderTemoinEpoux2() {
    viderChampsTemoin('epoux', 2);
}

function viderTemoinEpouse1() {
    viderChampsTemoin('epouse', 1);
}

function viderTemoinEpouse2() {
    viderChampsTemoin('epouse', 2);
}

// Fonction générique pour vider les champs d'un témoin
function viderChampsTemoin(type, numero) {
    const champs = [
        `nom_t_${type}_${numero}`,
        `prenom_t_${type}_${numero}`,
        `date_naissance_t_${type}_${numero}`,
        `code_localite_t_${type}_${numero}`,
        `code_nationalite_t_${type}_${numero}`,
        `code_profession_t_${type}_${numero}`,
        `code_type_document_t_${type}_${numero}`,
        `numero_document_t_${type}_${numero}`
    ];

    champs.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.value = '';
            // Réinitialiser le mode lecture simple
            element.classList.remove('form-control-readonly');
            element.style.backgroundColor = '';
            element.style.color = '';
            element.readOnly = false;
            element.disabled = false;
        }
    });

    if (typeof flashAlert !== 'undefined') {
        flashAlert("Champs vidés", "info", "Les champs du témoin ont été vidés.");
    }
}

// Fonction pour déterminer le code de situation matrimoniale basé sur les données de recherche
function determinerCodeSituationMatrimoniale(situationData) {
    if (!situationData || !situationData.statut) {
        return null;
    }

    switch(situationData.statut) {
        case 'celibataire':
            // Personne célibataire - pas de situation matrimoniale spécifique dans notre liste
            return null;
        case 'marie_monogamie':
            // Personne mariée en monogamie - correspond à "Mariage état civil"
            return 'SMAT_0001';
        case 'polygame':
            // Personne polygame - correspond à "Mariage état civil"
            return 'SMAT_0001';
        case 'divorce':
            // Personne divorcée
            return 'SMAT_0005';
        case 'veuf':
            // Personne veuve
            return 'SMAT_0006';
        default:
            return null;
    }
}

// Écouter les changements sur l'option de mariage
document.addEventListener('DOMContentLoaded', function() {
    const optionMariageSelect = document.getElementById('option_mariage');
    if (optionMariageSelect) {
        optionMariageSelect.addEventListener('change', verifierOptionMariage);
    }

    // Test de connexion au chargement de la page (pour débogage)
    // Décommentez la ligne suivante pour tester la connexion
    // testerConnexion();

    // Configurer le bouton suivant du wizard
    configurerBoutonSuivant();
});
