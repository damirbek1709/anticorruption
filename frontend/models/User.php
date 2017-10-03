<?php
/**
 * Created by PhpStorm.
 * User: damir
 * Date: 11.09.2017
 * Time: 13:15
 */
namespace frontend\models;

use dektrium\user\models\User as BaseUser;

class User extends BaseUser
{
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }
}
?>