<?php
$I = new AcceptanceTester($scenario);

//Login first
$I->wantTo('Test CSV Importer');
$I->wantTo('Login');
$I->amOnPage('/auth/login');
$I->fillField('login', 'test_saugat');
$I->fillField('password', 'test123');
$I->wantTo('Click login');
$I->executeJS("$('button[type=submit]').click()");

//Cancel the uploaded file.
$I->wantTo('Visit upload-csv page');
$I->amOnPage('/import-activity/upload-csv');
$I->wantTo('Attach File');
$I->attachFile('activity', 'other_fields_transaction.csv');
$I->click('Upload');
$I->seeInCurrentUrl('/import-activity/import-status');
$I->see('Import Activities');
$I->waitForText('Csv File Processing');
$I->see('Csv File Processing');
$I->waitForText('Csv File Processing Complete');
$I->see('Csv File Processing');

//Cancel the import activity
$I->click('Cancel');
$I->expect('Confirmation');
$I->click('Yes');
$I->seeInCurrentUrl('/import-activity/upload-csv');

//Delete test activity; comment this if uploading for first time
$I->wantTo('Delete test activity');
$I->amOnPage('/activity');
$I->click('Delete');
$I->click('Yes');
$I->see('Activity has been deleted successfully.');

//Successfully import the file if activity identifier already doesn't exist.
$I->wantTo('Visit upload-csv page');
$I->amOnPage('/import-activity/upload-csv');
$I->wantTo('Attach File');
$I->attachFile('activity', 'other_fields_transaction.csv');
$I->click('Upload');
$I->seeInCurrentUrl('/import-activity/import-status');
$I->see('Import Activities');
$I->waitForText('Csv File Processing');
$I->see('Csv File Processing');
$I->waitForText('Csv File Processing Complete');
$I->see('Csv File Processing');
$I->checkOption(['id' => 'checkAll']);
$I->wantTo('see if check all option select only valid object');
$I->seeCheckboxIsChecked('.valid-data-all input[type=checkbox]');
$I->dontSeeCheckboxIsChecked('.invalid-data-all input[type=checkbox]');
$I->wantTo('Validate element invalid data list errors');
$I->seeElement('div.invalid-data-all');
$I->wantTo('know if invalid element found');
$I->waitForElementVisible('#submit-valid-activities');
$I->click('Import');
$I->waitForText('Activities successfully imported.');
$I->see('Activities successfully imported.');


