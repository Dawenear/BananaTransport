<?php

class DeliveryCest
{
    const JSON_FILE = 'deliveryNotes.json';
    const FILE_PROCESS_ROUTE = '/processRoute.php';
    const RESPONSE_JSON = '/../_data/response.json';

    public function _before(AcceptanceTester $I)
    {
    }

    public function deliveryTest(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Select json file to process route:');
        $I->attachFile('input#jsonFile', self::JSON_FILE);
        $I->click('Process Route', '#processRoute');

        $I->amOnPage(self::FILE_PROCESS_ROUTE);
        $I->see(file_get_contents(__DIR__ . self::RESPONSE_JSON));
    }
}
