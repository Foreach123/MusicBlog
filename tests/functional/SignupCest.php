<?php

namespace tests\functional;

use Yii;

class SignupCest
{
    public function openSignupPage(\FunctionalTester $I)
    {
        $I->amOnPage('/auth/signup');
        $I->see('Sign Up');
        $I->seeResponseCodeIs(200);
    }

    public function registerNewUser(\FunctionalTester $I)
    {
        $I->amOnPage('/auth/signup');

        $email = 'test_' . uniqid() . '@example.com';

        $I->fillField('SignupForm[name]', 'TestUser');
        $I->fillField('SignupForm[email]', $email);
        $I->fillField('SignupForm[password]', '123456mm');

        $I->click('Sign Up');

        $I->dontSee('This email exists.');

        $I->seeRecord(\app\models\User::class, ['email' => $email]);
    }

    public function duplicateEmailTest(\FunctionalTester $I)
    {
        $I->haveInDatabase('users', [
            'name' => 'Tester',
            'email' => 'duplicate@example.com',
            'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'role' => 'user',
        ]);

        $I->amOnPage('/auth/signup');

        $I->fillField('SignupForm[name]', 'Tester2');
        $I->fillField('SignupForm[email]', 'duplicate@example.com');
        $I->fillField('SignupForm[password]', '123456');

        $I->click('Sign Up');

        $I->see('This email exists.');
    }
}
