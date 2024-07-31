<?php

return [
    "workers" => [
        "mail" => [
            "maxAttempt" => (int)getenv("SEND_EMAIL_QUEUE_MAX_ATTEMPT")
        ],
        "currency" => [
            "maxAttempt" => (int)getenv("CURRENCY_QUEUE_MAX_ATTEMPT")
        ],
        "subscription" => [
            "maxAttempt" => (int)getenv("SUBSCRIPTION_QUEUE_MAX_ATTEMPT")
        ]
    ],
];
