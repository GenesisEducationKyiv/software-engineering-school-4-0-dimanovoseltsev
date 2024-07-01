import http from "k6/http";
import {check, sleep} from "k6";

const BASE_URL = __ENV.BASE_URL || 'http://nginx';

export const options = {
    vus: 10,
    duration: '10m',
    rate: 50,
    timeUnit: "1s",
    thresholds: {
        'http_reqs{expected_response:true}': ['rate>10'],
    },
};

export default function () {
    check(http.get(`${BASE_URL}/rate`), {
        "status is 200": (r) => r.status == 200,
    });

    sleep(1);
}
