<?php

return [
    "workers" => [
        "mail-sent" => [
            "maxAttempt" => (int)getenv("MAIL_SENT_QUEUE_MAX_ATTEMPT")
        ]
    ],
];
