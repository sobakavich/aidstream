<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * Define custom actions here
     * @param AcceptanceTester $I
     */

    public function login(AcceptanceTester $I)
    {
        $I->amOnPage('/auth/login');
        $I->fillField('login', 'org_admin');
        $I->fillField('password', 'org1234');
        $I->click('Login');
        $I->see('Activities');
    }
}
