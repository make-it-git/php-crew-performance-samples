import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
    stages: [
        { duration: '30s', target: 20 },
        { duration: '1m', target: 20 },
        { duration: '30s', target: 0 },
    ],
    thresholds: {
        http_req_duration: ['p(95)<500'],
        http_req_failed: ['rate<0.1'],
    },
};

const BASE_URL = 'http://localhost:8080';

export default function () {
    const response = http.get(`${BASE_URL}/api/redis/test`);
    
    check(response, {
        'status is 200': (r) => r.status === 200,
        'response time OK': (r) => r.timings.duration < 500,
    });

    sleep(1);
} 