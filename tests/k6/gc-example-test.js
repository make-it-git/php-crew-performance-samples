import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate, Trend } from 'k6/metrics';
import exec from 'k6/execution';


// Определяем пользовательские метрики
const errorRate = new Rate('errors');
const latencyTrend = new Trend('latency');

const { SCENARIO } = __ENV;

const scenarios = {
    without_gc: {
        executor: 'constant-arrival-rate',
        rate: 1000, // 100 RPS
        timeUnit: '1s',
        duration: '5m',
        preAllocatedVUs: 1000,
        maxVUs: 1500,
    },
    // with_gc: {
    //     executor: 'constant-arrival-rate',
    //     rate: 1000, // 100 RPS
    //     timeUnit: '1s',
    //     duration: '5m',
    //     preAllocatedVUs: 1000,
    //     maxVUs: 1500,
    //     // startTime: '5m', // Начинаем после завершения первого сценария
    // }
};

export const options = {
    scenarios: SCENARIO ? { [SCENARIO]: scenarios[SCENARIO] } : scenarios,
    thresholds: {
        http_req_duration: ['p(95)<100'], // 95% запросов должны выполняться быстрее 0.1 секунды
        http_req_failed: ['rate<0.1'],     // Менее 10% запросов могут завершиться с ошибкой
        errors: ['rate<0.1'],              // Менее 10% ошибок
    },
};

const BASE_URL = 'http://localhost:8080/api';

export default function () {
    const currentScenario = exec.scenario.name;
    const gcThreshold = currentScenario === 'with_gc' ? 100 : 10_000;

    // Делаем запрос к endpoint'у
    const startTime = Date.now();
    const response = http.get(`${BASE_URL}/gc-example?gc_threshold=${gcThreshold}`);
    const endTime = Date.now();
    
    // Записываем метрики
    latencyTrend.add(endTime - startTime);
    
    // Проверяем ответ
    const checks = check(response, {
        'status is 200': (r) => r.status === 200,
        'response has data': (r) => r.json('data') !== undefined,
        'response has gc_threshold': (r) => r.json('gc_threshold') === gcThreshold,
        'response has garbage_collected field': (r) => r.json('garbage_collected') !== undefined,
    });

    // Отмечаем ошибки
    if (!checks) {
        errorRate.add(1);
    } else {
        errorRate.add(0);
    }
}
