<?php

class LoginCest
{
    // open login page
    public function openLoginPage(FunctionalTester $I)
    {
        $I->amOnPage('/auth/login');
        $I->see('Log In');             
        $I->seeResponseCodeIs(200);    // page loaded 
    }

    // login with existing user
    public function loginExistingUser(FunctionalTester $I)
    {
        // create user in DB before login
        $I->haveInDatabase('users', [
            'name' => 'Test User',
            'email' => 'login@example.com',
            'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
            'auth_key' => Yii::$app->security->generateRandomString()
        ]);

        // open login page
        $I->amOnPage('/auth/login');

        // fill login form
        $I->fillField('LoginForm[email]', 'login@example.com');
        $I->fillField('LoginForm[password]', '123456');

        // submit
        $I->click('Log In');

        // success message or redirect result
        $I->see('Smart music'); 
    }

    // invalid login test
    public function wrongPasswordTest(FunctionalTester $I)
    {
        $I->amOnPage('/auth/login');

        $I->fillField('LoginForm[email]', 'login@example.com');
        $I->fillField('LoginForm[password]', 'wrongpass');

        $I->click('Log In');

        $I->see('Невірний email або пароль.');
    }
}
