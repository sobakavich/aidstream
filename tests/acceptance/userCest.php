<?php

class UserCest
{

    public function _before(AcceptanceTester $I)
    {
        $I->login($I);
    }

    public function it_add_user(AcceptanceTester $I)
    {
        $I->amOnPage('admin/list-users');
        $I->click('Add User');
        $I->amOnPage('admin/register-user');
        $I->see('User Information');
        $I->fillField('first_name', 'Ijl');
        $I->fillField('last_name', 'kjl');
        $I->fillField('email', 'ijk@kji.com');
        $I->fillField('username', 'ijk_kji');
        $I->fillField('password', 'ijk1234');
        $I->fillField('password_confirmation', 'ijk1234');
        $I->checkOption('.field1');
        $I->click('Sign Up');
        $I->see('User has been created successfully.');
    }

    public function it_edit_logged_in_user(AcceptanceTester $I)
    {
        $I->amOnPage('/activity');
        $I->click('span[class=avatar-img]');
        $I->click('My Profile');
        $I->amOnPage('user/profile');
        $I->click('Edit Profile');
        $I->fillField('organization_telephone', '9876543210');
        $I->attachFile('input[type="file"]', 'pic.jpg');
        $I->click('Submit');
    }
}
