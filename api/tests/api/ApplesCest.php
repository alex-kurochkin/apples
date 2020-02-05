<?php
declare(strict_types=1);

namespace tests\api;

use Codeception\Util\HttpCode;
use api\tests\ApiTester;
use common\fixtures\apples\AppleArFixture;
use common\fixtures\apples\AppleColorArFixture;
use common\fixtures\UserFixture;

class ApplesApiCest
{

    public function _fixtures()
    {
        return [
            'Apple' => AppleArFixture::class,
            'AppleColor' => AppleColorArFixture::class,
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ]
        ];
    }

    /**
     * @param ApiTester $I
     * @group ApplesAPI
     */
    public function testApplesGetApi(ApiTester $I)
    {
        $I->wantToTest('Access to /api/apples/list');
        $I->amBearerAuthenticated('tester-token');

        $I->sendGET('apples/list');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);

        $I->seeResponseContainsJson(
            [
                'data' => [
                    'apples' => [
                        [
                            'id' => 1,
                            'colorId' => 1,
                            'eatenPercent' => 0.25,
                            'createdAt' => '2020-01-05T12:20:33+00:00',
                            'fallenAt' => '2020-01-20T14:45:12+00:00',
                        ],
                        [
                            'id' => 2,
                            'colorId' => 2,
                            'eatenPercent' => 0,
                            'createdAt' => '2020-01-07T12:21:37+00:00',
                            'fallenAt' => null,
                        ],
                    ],
                    'appleColors' => [
                        ['id' => 1, 'color' => 'red'],
                        ['id' => 2, 'color' => 'green'],
                        ['id' => 3, 'color' => 'yellow'],
                        ['id' => 4, 'color' => 'orange'],
                        ['id' => 5, 'color' => 'blue'],
                    ],
                ],
                'result' => 'success',
                'message' => null,
            ]
        );

        $applesBefore = $I->grabDataFromResponseByJsonPath('$.data.apples')[0];
        return count($applesBefore);
    }

    /**
     * @depends testApplesGetApi
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws \Exception
     */
    public function testApplesCreateApi(ApiTester $I)
    {
        $countBefore = $this->testApplesGetApi($I);

        $I->wantToTest('Access to /api/apples/create');
        $I->amBearerAuthenticated('tester-token');


        $I->sendGET('apples/create');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);

        $countNewApples = $I->grabDataFromResponseByJsonPath('$.data')[0];

        $I->sendGET('apples/list');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);
        $applesAfter = $I->grabDataFromResponseByJsonPath('$.data.apples')[0];

        $I->assertEquals($countNewApples + $countBefore, count($applesAfter));
    }
}
