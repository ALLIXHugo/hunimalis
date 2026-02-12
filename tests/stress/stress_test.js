import http from 'k6/http';
import { sleep, check } from 'k6';

// CONFIGURATION : Le scénario de montée en charge
export const options = {
    stages: [
        { duration: '30s', target: 10 }, // Phase 1 : 10 utilisateurs en 30s
        { duration: '1m', target: 50 },  // Phase 2 : Montée à 50 utilisateurs (le stress)
        { duration: '30s', target: 0 },  // Phase 3 : Redescente
    ],
    thresholds: {
        http_req_duration: ['p(95)<500'], // 95% des requêtes doivent être < 500ms
    },
};

export default function () {
    // 1. Visiter la page d'accueil
    let res = http.get('http://51.83.36.122:2408');

    // 2. Vérifications (Checks)
    check(res, {
        'statut est 200': (r) => r.status === 200,
        'page contient Bienvenue': (r) => r.body.includes('Bienvenue'),
    });

    sleep(1); // Simule le temps de lecture de l'utilisateur
}
