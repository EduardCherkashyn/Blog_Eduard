<?php namespace App\Tests;

class RegistrationCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/registration');
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        $I->fillField('registration[email]', 'alin7@ukr.net');
        $I->fillField('registration[name]', 'kitten');
        $I->fillField('registration[plainpassword]', 'kitten');
        $I->click('Submit');
        $I->see('Please sign in', 'h1');
        $I->fillField('login[email]','edikcherkashyn@ukr.net');
        $I->fillField('login[password]','12345');
        $I->click('Submit');
        $I->see('Add a new Article','a');
        $I->click(['class' => 'addArticle']);
        $I->see('List of Articles','a');

    }
}
