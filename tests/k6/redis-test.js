import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate, Trend } from 'k6/metrics';

const { SCENARIO } = __ENV;

const errorRate = new Rate('errors');
const latencyTrend = new Trend('latency');

const scenarios = {
    default: {
        executor: 'constant-arrival-rate',
        rate: 1000, // 1000 RPS
        timeUnit: '1s',
        duration: '5m',
        preAllocatedVUs: 1000,
        maxVUs: 1500,
    },
};

export const options = {
    scenarios: SCENARIO ? { [SCENARIO]: scenarios[SCENARIO] } : scenarios,
    thresholds: {
        http_req_duration: ['p(95)<1000'], // 95% of requests should complete within 1s
        http_req_failed: ['rate<0.1'],     // Менее 10% запросов могут завершиться с ошибкой
        errors: ['rate<0.1'],              // Менее 10% ошибок
    },
};

const BASE_URL = 'http://localhost:8080';

export default function () {
    const response = http.get(`${BASE_URL}/api/redis/test`);
    // const response = http.get(`${BASE_URL}/api/redis/test-pipelined`);
    
    check(response, {
        'status is 200': (r) => r.status === 200,
        'response time OK': (r) => r.timings.duration < 500,
    });

    sleep(1);
} 