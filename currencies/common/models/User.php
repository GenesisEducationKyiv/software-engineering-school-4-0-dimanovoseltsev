<?php

namespace common\models;

use yii\web\IdentityInterface;

class User implements IdentityInterface
{
    /**
     * @param $id
     * @return null
     */
    public static function findIdentity($id)
    {
        return null;
    }

    /**
     * @param $token
     * @param $type
     * @return null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return 0;
    }

    /**
     * @return null
     */
    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return null;
    }
}
