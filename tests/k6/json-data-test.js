import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate, Trend } from 'k6/metrics';
import exec from 'k6/execution';


// Определяем пользовательские метрики
const errorRate = new Rate('errors');
const latencyTrend = new Trend('latency');

const { SCENARIO } = __ENV;

const scenarios = {
    // json: {
    //     executor: 'constant-arrival-rate',
    //     rate: 100, // 100 RPS
    //     timeUnit: '1s',
    //     duration: '5m',
    //     preAllocatedVUs: 100,
    //     maxVUs: 100,
    // },
    string: {
        executor: 'constant-arrival-rate',
        rate: 100, // 100 RPS
        timeUnit: '1s',
        duration: '5m',
        preAllocatedVUs: 100,
        maxVUs: 100,
    }
};

export const options = {
    scenarios: SCENARIO ? { [SCENARIO]: scenarios[SCENARIO] } : scenarios,
    thresholds: {
        http_req_duration: ['p(95)<1000'], // 95% of requests should complete within 1s
        http_req_failed: ['rate<0.1'],     // Менее 10% запросов могут завершиться с ошибкой
        errors: ['rate<0.1'],              // Менее 10% ошибок
    },
};

const BASE_URL = 'http://localhost:8080/api';

export default function () {
    const currentScenario = exec.scenario.name;
    const kind = currentScenario === 'json' ? 'json-data' : 'string-data';

    const max = 10000;
    const min = 1;
    const id = Math.floor(Math.random() * (max - min + 1)) + min;
    const startTime = Date.now();
    const response = http.get(`${BASE_URL}/${kind}/${id}`);
    const endTime = Date.now();
    
    // Record metrics
    latencyTrend.add(endTime - startTime);
    
    // Check response
    const checks = check(response, {
        'status is 200': (r) => r.status === 200,
    });

    // Record errors
    if (!checks) {
        errorRate.add(1);
    } else {
        errorRate.add(0);
    }
}
