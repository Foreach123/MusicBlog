<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{

    public function rules()
    {
        return [
            [['name', 'email', 'password_hash', 'auth_key'], 'safe'],
        ];
    }


    // DB table
    public static function tableName()
    {
        return 'users';
    }

    // check password
    public function validatePassword($password)
    {
        return password_verify($password, $this->password_hash);
    }

    // hash password
    public function setPassword($password)
    {
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
    }

    // generate auth key
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    // find user by ID
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    // token auth
    public static function findIdentityByAccessToken($token, $type = null) {}

    // return user ID
    public function getId()
    {
        return $this->id;
    }

    // return saved auth key
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    // checking user role
    public function isAdmin(): bool
    {
        return isset($this->role) && $this->role === 'admin';
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['user_id' => 'id']);
    }
}
