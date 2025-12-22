<?php

class AuthTestCest
{
    public function openLoginPage(FunctionalTester $I)
    {
        $I->amOnPage('/auth/login');
        $I->see('Authorization');
        $I->seeResponseCodeIs(200);
    }

    public function signupNewUser(FunctionalTester $I)
    {
        $I->amOnPage('/auth/signup');

        $I->fillField('SignupForm[name]', 'Test User');
        $I->fillField('SignupForm[email]', 'test@example.com');
        $I->fillField('SignupForm[password]', '123456');

        $I->click('Sign Up');

        $I->seeInDatabase('users', ['email' => 'test@example.com']);
    }

    public function loginUser(FunctionalTester $I)
    {
        $I->amOnPage('/auth/login');

        $I->fillField('LoginForm[email]', 'test@example.com');
        $I->fillField('LoginForm[password]', '123456');

        $I->click('Log In');
        $I->see(''); 
    }
}
