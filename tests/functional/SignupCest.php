<?php

class SignupCest
{
    // open signup page
    public function openSignupPage(FunctionalTester $I)
    {
        $I->amOnPage('/auth/signup');
        $I->see('Sign Up');
        $I->seeResponseCodeIs(200);
    }

    // register user
    public function registerNewUser(FunctionalTester $I)
    {
        $I->amOnPage('/auth/signup');

        // fill signup form
        $I->fillField('SignupForm[name]', 'TestUser');
        $I->fillField('SignupForm[email]', 'test@example.com');
        $I->fillField('SignupForm[password]', '123456mm');


        // submit
        $I->click('Sign Up');

        // check DB
        $I->seeInDatabase('users', [
            'name' => 'TestUser'
        ]);
    }

    // try to register user with duplicate email
    public function duplicateEmailTest(FunctionalTester $I)
    {
        // first create user
        $I->haveInDatabase('users', [
            'name' => 'Tester',
            'email' => 'duplicate@example.com',
            'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
            'auth_key' => Yii::$app->security->generateRandomString()
        ]);

        // signup page
        $I->amOnPage('/auth/signup');

        $I->fillField('SignupForm[name]', 'Tester2');
        $I->fillField('SignupForm[email]', 'duplicate@example.com');
        $I->fillField('SignupForm[password]', '123456');

        $I->click('Sign Up');

        // validation message
        $I->see('This email exists.');
    }
}
