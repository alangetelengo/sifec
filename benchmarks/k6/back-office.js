/**
 * Test de charge back-office SIFEC (session web + CSRF).
 *
 * Prérequis : compte SANS 2FA et avec affectation active (sinon 302 vers erreur ou 2FA).
 *
 * Usage :
 *   k6 run -e BASE_URL=http://sifec.test -e SIFEC_EMAIL=test@example.com -e SIFEC_PASSWORD=secret benchmarks/k6/back-office.js
 *
 * Variables d'environnement :
 *   BASE_URL       Même valeur que APP_URL dans .env (sans slash final), ex. http://192.168.100.11:8010/sifec
 *   SIFEC_EMAIL    Email du compte de test
 *   SIFEC_PASSWORD Mot de passe
 *   THINK_MS       Pause entre login et page suivante (défaut 500)
 */

import http from 'k6/http';
import { check, fail, sleep } from 'k6';
import { Rate } from 'k6/metrics';

const loginFailRate = new Rate('sifec_login_failed');

function baseUrl() {
  const u = __ENV.BASE_URL || 'http://sifec.test';
  return u.replace(/\/$/, '');
}

function extractCsrfToken(html) {
  if (!html) {
    return null;
  }
  const patterns = [
    /name="_token"\s+value="([^"]+)"/,
    /name='_token'\s+value='([^']+)'/,
    /value="([^"]+)"\s+name="_token"/,
    /value='([^']+)'\s+name='_token'/,
    /name="csrf-token"\s+content="([^"]+)"/i,
    /content="([^"]+)"\s+name="csrf-token"/i,
  ];
  for (const re of patterns) {
    const m = html.match(re);
    if (m) {
      return m[1];
    }
  }
  return null;
}

export const options = {
  stages: [
    { duration: '30s', target: 5 },
    { duration: '1m', target: 10 },
    { duration: '30s', target: 0 },
  ],
  thresholds: {
    http_req_failed: ['rate<0.1'],
    http_req_duration: ['p(95)<8000'],
    sifec_login_failed: ['rate<0.3'],
  },
};

export default function () {
  const base = baseUrl();
  const email = __ENV.SIFEC_EMAIL;
  const password = __ENV.SIFEC_PASSWORD;
  if (!email || !password) {
    fail(
      'Définir SIFEC_EMAIL et SIFEC_PASSWORD (ex. -e SIFEC_EMAIL=... -e SIFEC_PASSWORD=...)',
    );
  }

  const jar = http.cookieJar();

  const resLogin = http.get(`${base}/login`, {
    jar,
    tags: { name: 'GET_login' },
  });

  check(resLogin, {
    'login page 200': (r) => r.status === 200,
  }) || loginFailRate.add(1);

  const token = extractCsrfToken(resLogin.body || '');
  if (!token) {
    loginFailRate.add(1);
    const preview = (resLogin.body || '')
      .substring(0, 200)
      .replace(/\s+/g, ' ')
      .trim();
    console.error(
      `Jeton CSRF introuvable — HTTP ${resLogin.status}, URL: ${resLogin.url}. ` +
        `Vérifier que BASE_URL = APP_URL (.env), ex. http://192.168.100.11:8010/sifec sans slash final. ` +
        `Début de réponse: ${preview}`,
    );
    return;
  }

  const payload = [
    `email=${encodeURIComponent(email)}`,
    `password=${encodeURIComponent(password)}`,
    `_token=${encodeURIComponent(token)}`,
  ].join('&');

  const resPost = http.post(`${base}/store`, payload, {
    jar,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      Referer: `${base}/login`,
    },
    redirects: 5,
    tags: { name: 'POST_store' },
  });

  const on2fa =
    (resPost.url && resPost.url.includes('two-factor')) ||
    (resPost.body || '').includes('two-factor/verify');

  const okPost =
    check(resPost, {
      'POST store 2xx': (r) => r.status >= 200 && r.status < 400,
    }) && check(resPost, { 'pas redirection 2FA': () => !on2fa });

  loginFailRate.add(!okPost);

  if (on2fa) {
    console.warn(
      'Flux 2FA détecté — utiliser un compte sans 2FA pour ce script de charge.',
    );
    return;
  }

  const think = parseInt(__ENV.THINK_MS || '500', 10) / 1000;
  if (think > 0) {
    sleep(think);
  }

  const resDash = http.get(`${base}/`, {
    jar,
    tags: { name: 'GET_dashboard' },
  });

  check(resDash, {
    'dashboard / 200 ou redirect auth': (r) =>
      r.status === 200 || r.status === 302,
  });
}
