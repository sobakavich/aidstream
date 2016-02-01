<?php

$I = new AcceptanceTester($scenario);
$I->amOnPage('auth/register');
$I->see('Register');
$I->fillField('organization_name', 'testpqr');
$I->fillField('organization_address', 'pqr');
$I->fillField('organization_user_identifier', 'testpqr');
$I->fillField('first_name', 'test');
$I->fillField('last_name', 'pqr');
$I->fillField('email', 'pqr@test.com');
$I->selectOption('country', 'BJ');
$I->fillField('password', 'pqr1234');
$I->fillField('password_confirmation', 'pqr1234');
$I->click('Register');
$I->amOnPage('auth/login');
