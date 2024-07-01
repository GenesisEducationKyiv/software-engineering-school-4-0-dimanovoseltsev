import http from "k6/http";
import {check, sleep} from "k6";

const BASE_URL = __ENV.BASE_URL || 'http://nginx';

export const options = {
    vus: 15,
    duration: '10m',
};

export default function () {
    let hash = Math.random().toString(16).slice(2);
    let body = {
        "email": `u-${hash}@gmail.com`
    }

    let res = http.post(`${BASE_URL}/subscribe`, JSON.stringify(body), {
        headers: {
            "Content-Type": "application/json",
        },
    });
    check(res, {"status is 200": (res) => res.status === 200});
    sleep(1);
}
