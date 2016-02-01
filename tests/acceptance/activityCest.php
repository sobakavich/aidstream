<?php

/**
 * Class ActivityCest
 */
class ActivityCest
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
    public function it_add_activity_manually(AcceptanceTester $I)
    {
        $I->amOnPage('/activity');
        $I->see('activities');
        $I->click('a[data-toggle=dropdown]');
        $I->click('Add Activity Manually');
        $I->amOnPage('/activity/create');
        $I->fillField('activity_identifier', 'tests');
        $I->click('Create Activity');
        $I->see('Activity has been created successfully.');
    }

    public function it_change_settings(AcceptanceTester $I)
    {
        $I->amOnPage('/settings');
        $I->fillField('reporting_organization_info[0][reporting_organization_identifier]', 'abc');
        $I->selectOption('reporting_organization_info[0][reporting_organization_type]', '15');
        $I->fillField('default_field_values[0][default_hierarchy]', '123');
        $I->selectOption('default_field_values[0][default_tied_status]', '4');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_other_identifier(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/other-identifier');
        $I->fillField('other_identifier[0][reference]', 'test');
        $I->selectOption('other_identifier[0][type]', 'A2');
        $I->fillField('other_identifier[0][owner_org][0][reference]', 'test');
        $I->fillField('other_identifier[0][owner_org][0][narrative][0][narrative]', 'test');
        $I->selectOption('other_identifier[0][owner_org][0][narrative][0][language]', 'ab');
        $I->click('Save');
        $I->see('Other Activity Identifier has been updated successfully.');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_title(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/title');
        $I->fillField('narrative[0][narrative]', 'test');
        $I->selectOption('narrative[0][language]', 'ar');
        $I->click('button[data-collection=narrative]');
        $I->fillField('narrative[1][narrative]', 'testing');
        $I->selectOption('narrative[1][language]', 'ae');
        $I->click('Save');
        $I->see('Activity Title has been updated successfully.');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_description(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/description');
        $I->selectOption('description[0][type]', '3');
        $I->fillField('description[0][narrative][0][narrative]', 'test');
        $I->selectOption('description[0][narrative][0][language]', 'av');
        $I->click('button[data-collection=description]');
        $I->selectOption('description[1][type]', '1');
        $I->fillField('description[1][narrative][0][narrative]', 'bas');
        $I->selectOption('description[1][narrative][0][language]', 'ab');
        $I->click('button[type=submit]');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_activity_status(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/activity-status');
        $I->selectOption('activity_status', '4');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_activity_date(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/activity-date');
        $I->fillField('activity_date[0][date]', '2016-01-27');
        $I->selectOption('activity_date[0][type]', '1');
        $I->fillField('activity_date[0][narrative][0][narrative]', 'abc');
        $I->selectOption('activity_date[0][narrative][0][language]', 'ay');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_contact_info(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/contact-info');
        $I->selectOption('contact_info[0][type]', '4');
        $I->fillField('contact_info[0][organization][0][narrative][0][narrative]', 'testabc');
        $I->selectOption('contact_info[0][organization][0][narrative][0][language]', 'ee');
        $I->fillField('contact_info[0][department][0][narrative][0][narrative]', 'test');
        $I->selectOption('contact_info[0][department][0][narrative][0][language]', 'ar');
        $I->fillField('contact_info[0][telephone][0][telephone]', '9870654321');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_activity_scope(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/activity-scope');
        $I->selectOption('activity_scope', '3');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_participating_org(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/participating-organization');
        $I->selectOption('participating_organization[0][organization_role]', '1');
        $I->fillField('participating_organization[0][identifier]', 'test');
        $I->selectOption('participating_organization[0][organization_type]', '70');
        $I->fillField('participating_organization[0][narrative][0][narrative]', 'abcd');
        $I->selectOption('participating_organization[0][narrative][0][language]', 'bm');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_recipient_country(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/recipient-country');
        $I->selectOption('recipient_country[0][country_code]', 'AR');
        $I->fillField('recipient_country[0][percentage]', '78');
        $I->fillField('recipient_country[0][narrative][0][narrative]', 'test');
        $I->selectOption('recipient_country[0][narrative][0][language]', 'av');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_recipient_region(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/recipient-region');
        $I->selectOption('recipient_region[0][region_code]', '289');
        $I->selectOption('recipient_region[0][region_vocabulary]', '2');
        $I->fillField('recipient_region[0][vocabulary_uri]', 'http://abc.com');
        $I->fillField('recipient_region[0][percentage]', '67');
        $I->fillField('recipient_region[0][narrative][0][narrative]', 'test');
        $I->selectOption('recipient_region[0][narrative][0][language]', 'ba');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_location(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/location');
        $I->fillField('location[0][reference]', 'test');
        $I->selectOption('location[0][location_reach][0][code]', '2');
        $I->selectOption('location[0][location_id][0][vocabulary]', 'A3');
        $I->fillField('location[0][location_id][0][code]', '23');
        $I->selectOption('location[0][administrative][0][vocabulary]', 'A4');
        $I->fillField('location[0][administrative][0][code]', '12');
        $I->fillField('location[0][administrative][0][level]', '23');
        $I->fillField('location[0][point][0][srs_name]', 'test');
        $I->fillField('location[0][point][0][position][0][latitude]', '66.4957404570233');
        $I->fillField('location[0][point][0][position][0][longitude]', '35.595703125');
        $I->selectOption('location[0][exactness][0][code]', '1');
        $I->selectOption('location[0][location_class][0][code]', '4');
        $I->selectOption('location[0][feature_designation][0][code]', 'MFGQ');
        $I->click('button[data-collection=recipient_country]');
        $I->fillField('location[1][reference]', 'tester');
        $I->selectOption('location[1][location_reach][0][code]', '1');
        $I->selectOption('location[1][location_id][0][vocabulary]', 'G1');
        $I->fillField('location[1][location_id][0][code]', '98');
        $I->selectOption('location[1][administrative][0][vocabulary]', 'A4');
        $I->fillField('location[1][administrative][0][code]', '45');
        $I->fillField('location[1][administrative][0][level]', '23');
        $I->fillField('location[1][point][0][srs_name]', 'testmnb');
        $I->fillField('location[1][point][0][position][0][latitude]', '64.84893726357947');
        $I->fillField('location[1][point][0][position][0][longitude]', '-19.16015625');
        $I->selectOption('location[1][exactness][0][code]', '2');
        $I->selectOption('location[1][location_class][0][code]', '3');
        $I->selectOption('location[1][feature_designation][0][code]', 'PPQ');
        $I->click('Save');
        $I->see('Location has been updated successfully.');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_sector(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/sector');
        $I->selectOption('sector[0][sector_vocabulary]', '99');
        $I->fillField('sector[0][vocabulary_uri]', 'http://example.com');
        $I->fillField('sector[0][sector_text]', 'sector-test');
        $I->fillField('sector[0][percentage]', '56');
        $I->fillField('sector[0][narrative][0][narrative]', 'test-n');
        $I->selectOption('sector[0][narrative][0][language]', 'bs');
        $I->click('button[data-collection=sector_narrative]');
        $I->fillField('sector[0][narrative][1][narrative]', 'test-t');
        $I->selectOption('sector[0][narrative][1][language]', 'da');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_policy_marker(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/policy-marker');
        $I->selectOption('policy_marker[0][vocabulary]', '99');
        $I->fillField('policy_marker[0][vocabulary_uri]', 'http://abc.com');
        $I->selectOption('policy_marker[0][significance]', '3');
        $I->selectOption('policy_marker[0][policy_marker]', '5');
        $I->fillField('policy_marker[0][narrative][0][narrative]', 'policy-test');
        $I->selectOption('policy_marker[0][narrative][0][language]', 'ch');
        $I->click('button[data-collection=policy_marker]');
        $I->selectOption('policy_marker[1][vocabulary]', '1');
        $I->fillField('policy_marker[1][vocabulary_uri]', 'http://asd.com');
        $I->selectOption('policy_marker[1][significance]', '3');
        $I->selectOption('policy_marker[1][policy_marker]', '3');
        $I->fillField('policy_marker[1][narrative][0][narrative]', 'test-maker');
        $I->selectOption('policy_marker[1][narrative][0][language]', 'an');
        $I->click('Save');
        $I->see('Policy Marker has been updated successfully.');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_country_budget_item(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/country-budget-items');
        $I->selectOption('country_budget_item[0][vocabulary]', '2');
        $I->selectOption('country_budget_item[0][budget_item][0][code]', '1.3.5');
        $I->fillField('country_budget_item[0][budget_item][0][percentage]', '23');
        $I->fillField('country_budget_item[0][budget_item][0][description][0][narrative][0][narrative]', 'test');
        $I->selectOption('country_budget_item[0][budget_item][0][description][0][narrative][0][language]', 'af');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_budget(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/budget');
        $I->selectOption('budget[0][budget_type]', '1');
        $I->selectOption('budget[0][status]', '2');
        $I->fillField('budget[0][period_start][0][date]', '2016-01-01');
        $I->fillField('budget[0][period_end][0][date]', '2016-01-30');
        $I->fillField('budget[0][value][0][amount]', '2400');
        $I->selectOption('budget[0][value][0][currency]', 'AFN');
        $I->fillField('budget[0][value][0][value_date]', '2016-01-23');
        $I->click('button[data-collection=budget]');
        $I->selectOption('budget[1][budget_type]', '2');
        $I->selectOption('budget[1][status]', '1');
        $I->fillField('budget[1][period_start][0][date]', '2016-01-15');
        $I->fillField('budget[1][period_end][0][date]', '2016-01-25');
        $I->fillField('budget[1][value][0][amount]', '234');
        $I->selectOption('budget[1][value][0][currency]', 'BAM');
        $I->fillField('budget[1][value][0][value_date]', '2016-01-26');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_planned_disbursement(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/planned-disbursement');
        $I->selectOption('planned_disbursement[0][planned_disbursement_type]', '1');
        $I->fillField('planned_disbursement[0][period_start][0][date]', '2016-02-01');
        $I->fillField('planned_disbursement[0][period_end][0][date]', '2016-02-16');
        $I->fillField('planned_disbursement[0][value][0][amount]', '2100');
        $I->selectOption('planned_disbursement[0][value][0][currency]', 'AUD');
        $I->fillField('planned_disbursement[0][value][0][value_date]', '2016-02-13');
        $I->fillField('planned_disbursement[0][provider_org][0][ref]', '12');
        $I->fillField('planned_disbursement[0][provider_org][0][activity_id]', '1');
        $I->fillField('planned_disbursement[0][provider_org][0][type]', '2');
        $I->fillField('planned_disbursement[0][provider_org][0][narrative][0][narrative]', 'test');
        $I->selectOption('planned_disbursement[0][provider_org][0][narrative][0][language]', 'av');
        $I->fillField('planned_disbursement[0][receiver_org][0][ref]', '45');
        $I->fillField('planned_disbursement[0][receiver_org][0][activity_id]', '23');
        $I->fillField('planned_disbursement[0][receiver_org][0][type]', '8');
        $I->fillField('planned_disbursement[0][receiver_org][0][narrative][0][narrative]', 'testing');
        $I->selectOption('planned_disbursement[0][receiver_org][0][narrative][0][language]', 'ca');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_capital_spend(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/capital-spend');
        $I->fillField('capital_spend', '10');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_document_link(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/document-link');
        $I->fillField('document_link[0][url]', 'http://abc.com');
        $I->selectOption('document_link[0][format]', 'application/atomicmail');
        $I->fillField('document_link[0][title][0][narrative][0][narrative]', 'test-dl');
        $I->selectOption('document_link[0][title][0][narrative][0][language]', 'ab');
        $I->selectOption('document_link[0][category][0][code]', 'A10');
        $I->selectOption('document_link[0][language][0][language]', 'ae');
        $I->fillField('document_link[0][document_date][0][date]', '2016-01-23');
        $I->click('button[data-collection=document_link]');
        $I->fillField('document_link[1][url]', 'http://asd.com');
        $I->selectOption('document_link[1][format]', 'application/alto-error+json');
        $I->fillField('document_link[1][title][0][narrative][0][narrative]', 'test-al1');
        $I->selectOption('document_link[1][title][0][narrative][0][language]', 'ay');
        $I->selectOption('document_link[1][category][0][code]', 'A11');
        $I->selectOption('document_link[1][language][0][language]', 'az');
        $I->fillField('document_link[1][document_date][0][date]', '2016-02-23');
        $I->click('Save');
        $I->see('Document Link has been updated successfully.');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_related_activity(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/related-activity');
        $I->selectOption('related_activity[0][relationship_type]', '4');
        $I->fillField('related_activity[0][activity_identifier]', 'test123');
        $I->click('button[data-collection=related_activity]');
        $I->selectOption('related_activity[1][relationship_type]', '1');
        $I->fillField('related_activity[1][activity_identifier]', 'abc123');
        $I->click('Save');
        $I->see('Related Activity has been updated successfully.');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_condition(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/condition');
        $I->selectOption('condition_attached', '1');
        $I->selectOption('condition[0][condition_type]', '2');
        $I->fillField('condition[0][narrative][0][narrative]', 'testabc');
        $I->selectOption('condition[0][narrative][0][language]', 'cr');
        $I->click('button[data-collection=condition]');
        $I->selectOption('condition[1][condition_type]', '3');
        $I->fillField('condition[1][narrative][0][narrative]', 'testanm');
        $I->selectOption('condition[1][narrative][0][language]', 'ff');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_legacy_data(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/legacy-data');
        $I->fillField('legacy_data[0][name]', 'test-ld');
        $I->fillField('legacy_data[0][value]', '12');
        $I->fillField('legacy_data[0][iati_equivalent]', 'asd');
        $I->click('button[data-collection=legacy_data]');
        $I->fillField('legacy_data[1][name]', 'abc');
        $I->fillField('legacy_data[1][value]', '230');
        $I->fillField('legacy_data[1][iati_equivalent]', 'asd');
        $I->click('Save');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_transaction(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/transaction');
        $I->click('Add New Transaction');
        $I->amOnPage('activity/47/transaction/create');
        $I->fillField('transaction[0][reference]', 'testabc');
        $I->selectOption('transaction[0][humanitarian]', '1');
        $I->selectOption('transaction[0][transaction_type][0][transaction_type_code]', '3');
        $I->fillField('transaction[0][transaction_date][0][date]', '2016-02-01');
        $I->fillField('transaction[0][value][0][amount]', '1200');
        $I->fillField('transaction[0][value][0][date]', '2016-02-13');
        $I->selectOption('transaction[0][value][0][currency]', 'AFN');
        $I->fillField('transaction[0][description][0][narrative][0][narrative]', 'testing');
        $I->selectOption('transaction[0][description][0][narrative][0][language]', 'ae');
        $I->fillField('transaction[0][provider_organization][0][organization_identifier_code]', '12');
        $I->fillField('transaction[0][provider_organization][0][provider_activity_id]', '1');
        $I->fillField('transaction[0][provider_organization][0][type]', 'abc');
        $I->fillField('transaction[0][provider_organization][0][narrative][0][narrative]', 'test-nar');
        $I->selectOption('transaction[0][provider_organization][0][narrative][0][language]', 'af');
        $I->fillField('transaction[0][receiver_organization][0][organization_identifier_code]', '34');
        $I->fillField('transaction[0][receiver_organization][0][receiver_activity_id]', '2');
        $I->fillField('transaction[0][receiver_organization][0][type]', 'asdfg');
        $I->fillField('transaction[0][receiver_organization][0][narrative][0][narrative]', 'testing');
        $I->selectOption('transaction[0][receiver_organization][0][narrative][0][language]', 'bh');
        $I->selectOption('transaction[0][disbursement_channel][0][disbursement_channel_code]', '2');
        $I->selectOption('transaction[0][sector][0][sector_vocabulary]', '3');
        $I->fillField('transaction[0][sector][0][vocabulary_uri]', 'http://qwe.com');
        $I->fillField('transaction[0][sector][0][sector_text]', 'abc');
        $I->fillField('transaction[0][sector][0][narrative][0][narrative]', 'narr');
        $I->selectOption('transaction[0][sector][0][narrative][0][language]', 'af');
        $I->selectOption('transaction[0][recipient_country][0][country_code]', 'AD');
        $I->fillField('transaction[0][recipient_country][0][narrative][0][narrative]', 'testing');
        $I->selectOption('transaction[0][recipient_country][0][narrative][0][language]', 'an');
        $I->selectOption('transaction[0][recipient_region][0][region_code]', '189');
        $I->selectOption('transaction[0][recipient_region][0][vocabulary]', '1');
        $I->fillField('transaction[0][recipient_region][0][vocabulary_uri]', 'http://xyz.com');
        $I->fillField('transaction[0][recipient_region][0][narrative][0][narrative]', 'asdf');
        $I->selectOption('transaction[0][recipient_region][0][narrative][0][language]', 'bo');
        $I->selectOption('transaction[0][flow_type][0][flow_type]', '30');
        $I->selectOption('transaction[0][finance_type][0][finance_type]', '210');
        $I->selectOption('transaction[0][aid_type][0][aid_type]', 'E02');
        $I->selectOption('transaction[0][tied_status][0][tied_status_code]', '4');
        $I->click('Create');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function it_add_results(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47/result');
        $I->click('Add Another Result');
        $I->amOnPage('activity/47/result/create');
        $I->selectOption('result[0][type]', '2');
        $I->selectOption('result[0][aggregation_status]', '1');
        $I->fillField('result[0][title][0][narrative][0][narrative]', 'testabc');
        $I->selectOption('result[0][title][0][narrative][0][language]', 'av');
        $I->fillField('result[0][description][0][narrative][0][narrative]', 'testdescp');
        $I->selectOption('result[0][description][0][narrative][0][language]', 'be');
        $I->selectOption('result[0][indicator][0][measure]', '1');
        $I->selectOption('result[0][indicator][0][ascending]', '1');
        $I->fillField('result[0][indicator][0][title][0][narrative][0][narrative]', 'testnar');
        $I->selectOption('result[0][indicator][0][title][0][narrative][0][language]', 'ab');
        $I->fillField('result[0][indicator][0][description][0][narrative][0][narrative]', 'abc');
        $I->selectOption('result[0][indicator][0][description][0][narrative][0][language]', 'ab');
        $I->selectOption('result[0][indicator][0][reference][0][vocabulary]', '2');
        $I->fillField('result[0][indicator][0][reference][0][code]', '23');
        $I->fillField('result[0][indicator][0][reference][0][indicator_uri]', 'http://asd.com');
        $I->fillField('result[0][indicator][0][baseline][0][year]', '2016');
        $I->fillField('result[0][indicator][0][baseline][0][value]', '12');
        $I->fillField('result[0][indicator][0][baseline][0][comment][0][narrative][0][narrative]', 'testasd');
        $I->selectOption('result[0][indicator][0][baseline][0][comment][0][narrative][0][language]', 'af');
        $I->fillField('result[0][indicator][0][period][0][period_start][0][date]', '2016-02-01');
        $I->fillField('result[0][indicator][0][period][0][period_end][0][date]', '2016-02-23');
        $I->fillField('result[0][indicator][0][period][0][target][0][value]', '2500');
        $I->fillField('result[0][indicator][0][period][0][target][0][location][0][ref]', 'asd');
        $I->fillField('result[0][indicator][0][period][0][target][0][dimension][0][name]', 'test');
        $I->fillField('result[0][indicator][0][period][0][target][0][dimension][0][value]', 'hjsdgh');
        $I->fillField('result[0][indicator][0][period][0][target][0][comment][0][narrative][0][narrative]', 'jjds');
        $I->selectOption('result[0][indicator][0][period][0][target][0][comment][0][narrative][0][language]', 'br');
        $I->fillField('result[0][indicator][0][period][0][actual][0][value]', '123');
        $I->fillField('result[0][indicator][0][period][0][actual][0][location][0][ref]', 'absd');
        $I->fillField('result[0][indicator][0][period][0][actual][0][dimension][0][name]', 'bsvh');
        $I->fillField('result[0][indicator][0][period][0][actual][0][dimension][0][value]', 'nfv');
        $I->fillField('result[0][indicator][0][period][0][actual][0][comment][0][narrative][0][narrative]', 'asdf');
        $I->selectOption('result[0][indicator][0][period][0][actual][0][comment][0][narrative][0][language]', 'am');
        $I->click('Save');
        $I->see('Activity Result has been created successfully.');
    }

    public function it_publish_activity(AcceptanceTester $I)
    {
        $I->amOnPage('activity/47');
        $I->click('Complete');
        $I->click('Verify');
        $I->click('Publish');
        $I->waitForElementVisible('.modal-content');
        $I->see('Are you sure you want to Publish?', '.modal-body');
        $I->click('Yes');
    }
}
