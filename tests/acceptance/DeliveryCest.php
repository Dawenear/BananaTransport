<?php 

class DeliveryCest
{
    const JSON_FILE = 'deliveryNotes.json';
    const FILE_PROCCESS_ROUTE = '/processRoute.php';

    public function _before(AcceptanceTester $I)
    {
    }

    public function deliveryTest(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Select json file to process route:');
        $I->attachFile('input#jsonFile', self::JSON_FILE);
        $I->click('Process Route', '#processRoute');

        $I->amOnPage(self::FILE_PROCCESS_ROUTE);
        $I->see(file_get_contents(__DIR__ . '/../_data/response.json'));
    }
}
