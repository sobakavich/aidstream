<?php

/**
 * Class OrganizationCest
 */
class OrganizationCest
{
    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        $I->login($I);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_reporting_organization(AcceptanceTester $I)
    {
        $I->amOnPage('organization/1/reportingOrg');
        $I->fillField('reporting_org[0][narrative][0][narrative]', 'abc');
        $I->selectOption('reporting_org[0][narrative][0][language]', 'an');
        $I->click('button[data-collection=narrative]');
        $I->fillField('reporting_org[0][narrative][1][narrative]', 'asdf');
        $I->selectOption('reporting_org[0][narrative][1][language]', 'ak');
        $I->click('Update');
        $I->see('Reporting Organization has been updated successfully.');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_name(AcceptanceTester $I)
    {
        $I->amOnPage('organization/1/name');
        $I->fillField('name[0][narrative]', 'test');
        $I->selectOption('name[0][language]', 'mn');
        $I->click('button[data-collection=narrative]');
        $I->fillField('name[1][narrative]', 'asdf');
        $I->selectOption('name[1][language]', 'dz');
        $I->click('Save');
        $I->see('Organization Name has been updated successfully.');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_total_budget(AcceptanceTester $I)
    {
        $I->amOnPage('organization/1/total-budget');
        $I->selectOption('total_budget[0][status]', '2');
        $I->fillField('total_budget[0][period_start][0][date]', '2016-01-01');
        $I->fillField('total_budget[0][period_end][0][date]', '2016-01-31');
        $I->fillField('total_budget[0][value][0][amount]', '3400');
        $I->selectOption('total_budget[0][value][0][currency]', 'BBD');
        $I->fillField('total_budget[0][value][0][value_date]', '2016-01-15');
        $I->fillField('total_budget[0][budget_line][0][reference]', 'test');
        $I->fillField('total_budget[0][budget_line][0][value][0][amount]', '6700');
        $I->selectOption('total_budget[0][budget_line][0][value][0][currency]', 'AFN');
        $I->fillField('total_budget[0][budget_line][0][value][0][value_date]', '2015-06-01');
        $I->fillField('total_budget[0][budget_line][0][narrative][0][narrative]', 'testing');
        $I->selectOption('total_budget[0][budget_line][0][narrative][0][language]', 'ca');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_recipient_org_budget(AcceptanceTester $I)
    {
        $I->amOnPage('organization/1/recipient-organization-budget');
        $I->selectOption('recipient_organization_budget[0][status]', '2');
        $I->fillField('recipient_organization_budget[0][recipient_organization][0][Ref]', 'testing');
        $I->fillField('recipient_organization_budget[0][narrative][0][narrative]', 'test-nar');
        $I->selectOption('recipient_organization_budget[0][narrative][0][language]', 'ae');
        $I->fillField('recipient_organization_budget[0][period_start][0][date]', '2016-01-02');
        $I->fillField('recipient_organization_budget[0][period_end][0][date]', '2016-01-31');
        $I->fillField('recipient_organization_budget[0][value][0][amount]', '789');
        $I->selectOption('recipient_organization_budget[0][value][0][currency]', 'ALL');
        $I->fillField('recipient_organization_budget[0][value][0][value_date]', '2016-01-15');
        $I->fillField('recipient_organization_budget[0][budget_line][0][reference]', 'testing');
        $I->fillField('recipient_organization_budget[0][budget_line][0][value][0][amount]', '8700');
        $I->selectOption('recipient_organization_budget[0][budget_line][0][value][0][currency]', 'BIF');
        $I->fillField('recipient_organization_budget[0][budget_line][0][value][0][value_date]', '2016-01-11');
        $I->fillField('recipient_organization_budget[0][budget_line][0][narrative][0][narrative]', 'testing');
        $I->selectOption('recipient_organization_budget[0][budget_line][0][narrative][0][language]', 'ae');
        $I->click('Update');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_recipient_region_budget(AcceptanceTester $I)
    {
        $I->amOnPage('organization/1/recipient-region-budget');
        $I->selectOption('recipient_region_budget[0][status]', '1');
        $I->selectOption('recipient_region_budget[0][recipient_region][0][vocabulary]', '2');
        $I->fillField('recipient_region_budget[0][recipient_region][0][vocabulary_uri]', 'http://asr.com');
        $I->selectOption('recipient_region_budget[0][recipient_region][0][code]', '89');
        $I->fillField('recipient_region_budget[0][recipient_region][0][narrative][0][narrative]', 'testing');
        $I->selectOption('recipient_region_budget[0][recipient_region][0][narrative][0][language]', 'ab');
        $I->fillField('recipient_region_budget[0][period_start][0][date]', '2016-02-01');
        $I->fillField('recipient_region_budget[0][period_end][0][date]', '2016-02-13');
        $I->fillField('recipient_region_budget[0][value][0][amount]', '3200');
        $I->selectOption('recipient_region_budget[0][value][0][currency]', 'BHD');
        $I->fillField('recipient_region_budget[0][value][0][value_date]', '2016-02-17');
        $I->fillField('recipient_region_budget[0][budget_line][0][reference]', 'asdtest');
        $I->fillField('recipient_region_budget[0][budget_line][0][value][0][amount]', '3400');
        $I->selectOption('recipient_region_budget[0][budget_line][0][value][0][currency]', 'AFN');
        $I->fillField('recipient_region_budget[0][budget_line][0][value][0][value_date]', '2016-02-10');
        $I->fillField('recipient_region_budget[0][budget_line][0][narrative][0][narrative]', 'adbtest');
        $I->selectOption('recipient_region_budget[0][budget_line][0][narrative][0][language]', 'da');
        $I->click('Save');
        $I->see('Organization Recipient Region Budget has been updated successfully.');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_recipient_country_budget(AcceptanceTester $I)
    {
        $I->amOnPage('organization/1/recipient-country-budget');
        $I->selectOption('recipient_country_budget[0][recipient_country][0][code]', 'AW');
        $I->fillField('recipient_country_budget[0][recipient_country][0][narrative][0][narrative]', 'testing');
        $I->selectOption('recipient_country_budget[0][recipient_country][0][narrative][0][language]', 'ae');
        $I->fillField('recipient_country_budget[0][period_start][0][date]', '2016-02-01');
        $I->fillField('recipient_country_budget[0][period_end][0][date]', '2016-02-25');
        $I->fillField('recipient_country_budget[0][value][0][amount]', '8769');
        $I->selectOption('recipient_country_budget[0][value][0][currency]', 'ALL');
        $I->fillField('recipient_country_budget[0][value][0][value_date]', '2016-01-01');
        $I->fillField('recipient_country_budget[0][budget_line][0][reference]', 'testing');
        $I->fillField('recipient_country_budget[0][budget_line][0][value][0][amount]', '3450');
        $I->selectOption('recipient_country_budget[0][budget_line][0][value][0][currency]', 'AED');
        $I->fillField('recipient_country_budget[0][budget_line][0][value][0][value_date]', '2016-02-04');
        $I->fillField('recipient_country_budget[0][budget_line][0][narrative][0][narrative]', 'asnvd');
        $I->selectOption('recipient_country_budget[0][budget_line][0][narrative][0][language]', 'ab');
        $I->click('button[data-collection=budget_line]');
        $I->fillField('recipient_country_budget[0][budget_line][1][reference]', 'abc');
        $I->fillField('recipient_country_budget[0][budget_line][1][value][0][amount]', '2389');
        $I->selectOption('recipient_country_budget[0][budget_line][1][value][0][currency]', 'AFN');
        $I->fillField('recipient_country_budget[0][budget_line][1][value][0][value_date]', '2016-02-03');
        $I->fillField('recipient_country_budget[0][budget_line][1][narrative][0][narrative]', 'asdfgh');
        $I->selectOption('recipient_country_budget[0][budget_line][1][narrative][0][language]', 'ab');
        $I->click('Save');
        $I->see('Organization Recipient Country Budget has been updated successfully.');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_total_expenditure(AcceptanceTester $I)
    {
        $I->amOnPage('organization/1/total-expenditure');
        $I->fillField('total_expenditure[0][period_start][0][date]', '2016-01-01');
        $I->fillField('total_expenditure[0][period_end][0][date]', '2016-02-12');
        $I->fillField('total_expenditure[0][value][0][amount]', '23450');
        $I->selectOption('total_expenditure[0][value][0][currency]', 'AMD');
        $I->fillField('total_expenditure[0][value][0][value_date]', '2016-01-19');
        $I->fillField('total_expenditure[0][expense_line][0][reference]', 'asdwer');
        $I->fillField('total_expenditure[0][expense_line][0][value][0][amount]', '5678');
        $I->selectOption('total_expenditure[0][expense_line][0][value][0][currency]', 'BGN');
        $I->fillField('total_expenditure[0][expense_line][0][value][0][value_date]', '2016-01-19');
        $I->fillField('total_expenditure[0][expense_line][0][narrative][0][narrative]', 'hsdhcfd');
        $I->selectOption('total_expenditure[0][expense_line][0][narrative][0][language]', 'am');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_document_link(AcceptanceTester $I)
    {
        $I->amOnPage('organization/1/document-link');
        $I->fillField('document_link[0][url]', 'http://xyz.com');
        $I->selectOption('document_link[0][format]', 'application/3gpp-ims+xml');
        $I->fillField('document_link[0][narrative][0][narrative]', 'testing');
        $I->selectOption('document_link[0][narrative][0][language]', 'af');
        $I->selectOption('document_link[0][category][0][code]', 'B11');
        $I->selectOption('document_link[0][language][0][language]', 'bg');
        $I->fillField('document_link[0][document_date][0][date]', '2016-01-01');
        $I->selectOption('document_link[0][recipient_country][0][code]', 'AI');
        $I->fillField('document_link[0][recipient_country][0][narrative][0][narrative]', 'sakdbcjd');
        $I->selectOption('document_link[0][recipient_country][0][narrative][0][language]', 'ae');
        $I->click('Save');
    }

    public function it_publish_org(AcceptanceTester $I)
    {
        $I->amOnPage('organization/1');
        $I->click('Complete');
        $I->click('Verify');
        $I->click('Publish');
        $I->waitForElementVisible('.modal-content');
        $I->see('Are you sure you want to Publish?', '.modal-body');
        $I->click('Yes');
    }
}
