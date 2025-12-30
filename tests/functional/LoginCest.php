<?php

namespace tests\functional;
use Yii;

class LoginCest
{
    public function openLoginPage(\FunctionalTester $I)
    {
        $I->amOnPage('/auth/login');
        $I->see('Log In');
        $I->seeResponseCodeIs(200);
    }

    public function loginExistingUser(\FunctionalTester $I)
    {
        // create user in DB
        $I->haveInDatabase('users', [
            'name' => 'Test User',
            'email' => 'login@example.com',
            'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'role' => 'user',
        ]);

        $I->amOnPage('/auth/login');
        $I->fillField('LoginForm[email]', 'login@example.com');
        $I->fillField('LoginForm[password]', '123456');
        $I->click('Log In');

        // after login user should see site header/brand
        $I->see('Smart music');
    }

    public function wrongPasswordTest(\FunctionalTester $I)
    {
        // create user
        $I->haveInDatabase('users', [
            'name' => 'Test User',
            'email' => 'login@example.com',
            'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'role' => 'user',
        ]);

        $I->amOnPage('/auth/login');
        $I->fillField('LoginForm[email]', 'login@example.com');
        $I->fillField('LoginForm[password]', 'wrongpass');
        $I->click('Log In');

        $I->see('Невірний email або пароль.');
    }
}
