<?php
declare(strict_types=1);

namespace tests\api;

use backend\tests\ApiTester;

class ApplesApiCest
{

    /**
     * @param ApiTester $I
     * @group ApplesAPI
     */
    public function testSmoke(ApiTester $I)
    {
        $I->wantToTest('Test to smoke');
    }
}
