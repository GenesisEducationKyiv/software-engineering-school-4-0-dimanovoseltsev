<?php

return [
    "workers" => [
        "mail" => [
            "maxAttempt" => (int)getenv("SEND_EMAIL_QUEUE_MAX_ATTEMPT")
        ]
    ],
];
