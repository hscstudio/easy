<?php 

class FirstCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
    }

    public function frontpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Easy PHP Framework');  
    }

    public function articlepageWithoutLoginWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/article');
        $I->see('Login');  
    }

    public function loginNormalWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/site/login');
        $I->fillField('username', 'admin');
        $I->fillField('password', '123456');
        $I->click('Login');
        $I->see('Easy PHP Framework');
    }

    public function loginWithWrongPasswordWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/site/login');
        $I->fillField('username', 'admin');
        $I->fillField('password', 'xyz');
        $I->click('Login');
        $I->see('Password not match');
    }

    public function loginWithWrongUsernameWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/site/login');
        $I->fillField('username', 'abc');
        $I->fillField('password', 'xyz');
        $I->click('Login');
        $I->see('Login fail!');
    }

    public function articlepageWithLoginWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/article');
        $I->see('Login'); 
        $I->fillField('username', 'admin');
        $I->fillField('password', '123456');
        $I->click('Login'); 
        $I->see('Easy PHP Framework');
        $I->amOnPage('/article');
        $I->see('List of articles');
    }
}
