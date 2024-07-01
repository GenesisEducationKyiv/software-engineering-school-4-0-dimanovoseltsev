<?php

namespace tests\components;

use yii\symfonymailer\Mailer;

class YiiMailer extends Mailer
{
    /**
     * @param $message
     * @return true
     */
    public function send($message): true
    {
        return true;
    }
}
