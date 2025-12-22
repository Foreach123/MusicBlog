<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user = null;

    // validation rules for login 
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],                  
            ['rememberMe', 'boolean'],           
            ['password', 'validatePassword'],   
        ];
    }

    // custom password validator
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {               
            $user = $this->getUser();             

            // if user doesn't exist OR password doesn't match 
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Невірний email або пароль.');
            }
        }
    }

    // login 
    public function login()
    {
        if ($this->validate()) { // validate form first
            return Yii::$app->user->login(
                $this->getUser(),                 // user model instance
                $this->rememberMe ? 3600 * 24 * 30 : 0
            );
        }
        return false;
    }

    // load once 
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(['email' => $this->email]); // find user by email
        }
        return $this->_user;
    }
}
