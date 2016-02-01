<?php

class SettingCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->login($I);
    }

    public function it_change_settings(AcceptanceTester $I)
    {
        $I->amOnPage('/settings');
        $I->fillField('reporting_organization_info[0][reporting_organization_identifier]', 'abc');
        $I->selectOption('reporting_organization_info[0][reporting_organization_type]', '15');
        $I->click('input[value=unsegmented]');
        $I->fillField('registry_info[0][publisher_id]', 'abc');
        $I->fillField('registry_info[0][api_id]', 'asd');
        $I->fillField('default_field_values[0][default_hierarchy]', '123');
        $I->selectOption('default_field_values[0][default_tied_status]', '4');
        $I->click('Save');
        $I->see('Settings has been updated successfully.');
    }
}
