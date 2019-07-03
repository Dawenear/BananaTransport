<?php

class RunFromTerminalCest
{
    const TERMINAL_RESPONSE_JSON = '/../_data/terminal_response.json';

    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function runFromTerminalTest(AcceptanceTester $I)
    {
        $I->amOnPage('/runFromTerminal.php');
        $I->see(file_get_contents(__DIR__ . self::TERMINAL_RESPONSE_JSON));
    }
}
