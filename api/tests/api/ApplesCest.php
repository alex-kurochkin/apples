<?php
declare(strict_types=1);

namespace tests\api;

use Codeception\Util\HttpCode;
use api\tests\ApiTester;
use common\fixtures\apples\AppleArFixture;
use common\fixtures\apples\AppleColorArFixture;
use common\fixtures\UserFixture;
use \ErrorException;
use \Exception;

/**
 * Class ApplesApiCest
 * @package tests\api
 *
 * PHP server and CLI timezone must be UTC
 */
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
     * @throws Exception
     */
    public function testApplesUnauthorizedAccessApi(ApiTester $I)
    {
        $I->wantToTest('to unauthorized access');

        $I->sendGET('apples');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Your request was made with invalid credentials.']);
    }

    /**
     * @depends testApplesGetApi
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesCreateApi(ApiTester $I)
    {
        $countBefore = $this->testApplesGetApi($I);

        $I->wantToTest('to CREATE apples');
        $I->amBearerAuthenticated('tester-token');

        $I->sendPOST('apples');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);

        $countNewApples = $I->grabDataFromResponseByJsonPath('$.data')[0];

        $I->sendGET('apples');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);
        $applesAfter = $I->grabDataFromResponseByJsonPath('$.data.apples')[0];

        $I->assertEquals($countNewApples + $countBefore, count($applesAfter));
    }

    /**
     * @param ApiTester $I
     * @return int
     * @throws Exception
     * @group ApplesAPI
     */
    public function testApplesGetApi(ApiTester $I)
    {
        $I->wantToTest('to GET list apples');
        $I->amBearerAuthenticated('tester-token');

        $I->sendGET('apples');
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
                            'eatenPercent' => 0,
                        ],
                        [
                            'id' => 2,
                            'colorId' => 2,
                            'eatenPercent' => 0.5,
                        ],
                        [
                            'id' => 3,
                            'colorId' => 2,
                            'eatenPercent' => 0,
                        ],
                        [
                            'id' => 4,
                            'colorId' => 1,
                            'eatenPercent' => 0,
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
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesDeleteUncreatedApi(ApiTester $I)
    {
        $I->wantToTest('to DELETE uncreated apple');
        $I->amBearerAuthenticated('tester-token');

        $I->sendDELETE('apples/100');
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Not found.']);
    }

    /**
     * @depends testApplesGetApi
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesDeleteApi(ApiTester $I)
    {
        $countBefore = $this->testApplesGetApi($I);

        $I->wantToTest('to DELETE apple');
        $I->amBearerAuthenticated('tester-token');

        $I->sendDELETE('apples/1');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);

        $I->sendGET('apples');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);
        $applesAfter = $I->grabDataFromResponseByJsonPath('$.data.apples')[0];

        $I->assertEquals($countBefore - 1, count($applesAfter));
    }

    /**
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesEatApi(ApiTester $I)
    {
        $I->wantToTest('to UPDATE apple');
        $I->amBearerAuthenticated('tester-token');

        $appleId = 2;
        $eatPercent = 0.15;

        $percentBefore = $this->getEatenPercent($I, $appleId);

        $I->sendPATCH('apples/' . $appleId . '/' . $eatPercent);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);

        $percentAfter = $this->getEatenPercent($I, $appleId);

        $I->assertEquals($percentBefore + $eatPercent, $percentAfter);
    }

    /**
     * @param ApiTester $I
     * @param int $appleId
     * @return float
     * @throws ErrorException
     * @throws Exception
     */
    private function getEatenPercent(ApiTester $I, int $appleId): float
    {
        $I->sendGET('apples');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);

        $apples = $I->grabDataFromResponseByJsonPath('$.data.apples')[0];

//        var_dump($apples);die;

        return $this->findEatenPercent($apples, $appleId);
    }

    /**
     * @param array $apples
     * @param int $appleId
     * @return float
     * @throws ErrorException
     */
    private function findEatenPercent(array $apples, int $appleId): float
    {
        foreach ($apples as $apple) {
            $apple = (object)$apple;
            if ($appleId === $apple->id) {
                return $apple->eatenPercent;
            }
        }

        throw new ErrorException('Apple with id' . $appleId . ' not found');
    }

    /**
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesEatZeroPercentsApi(ApiTester $I)
    {
        $I->wantToTest('to UPDATE apple try to lick');
        $I->amBearerAuthenticated('tester-token');

        $appleId = 1;
        $eatPercent = 0; // <--- Try to lick, it's possible. Why not? )))

        $I->sendPATCH('apples/' . $appleId . '/' . $eatPercent);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);
    }

    /**
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesEatUncreatedApi(ApiTester $I)
    {
        $I->wantToTest('to UPDATE uncreated apple');
        $I->amBearerAuthenticated('tester-token');

        $appleId = 100;
        $eatPercent = 0.1;

        $I->sendPATCH('apples/' . $appleId . '/' . $eatPercent);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Not found.']);
    }

    /**
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesEatByteMore100PercentsApi(ApiTester $I)
    {
        $I->wantToTest('to UPDATE apple to byte more then 100%');
        $I->amBearerAuthenticated('tester-token');

        $appleId = 1;
        $eatPercent = 1.15;

        $I->sendPATCH('apples/' . $appleId . '/' . $eatPercent);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Eaten Percent must be no greater than 1.']);
    }

    /**
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesEatMore100PercentApi(ApiTester $I)
    {
        $I->wantToTest('to UPDATE apple try to eat summary more than 100%');
        $I->amBearerAuthenticated('tester-token');

        $appleId = 2;
        $eatPercent = 0.6; // 0.5 + 0.6 = 1.1

        $I->sendPATCH('apples/' . $appleId . '/' . $eatPercent);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'You are trying to bite off more than the remaining apple']);
    }

    /**
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesEatUnripeApi(ApiTester $I)
    {
        $I->wantToTest('to UPDATE unripe apple');
        $I->amBearerAuthenticated('tester-token');

        $appleId = 3;
        $eatPercent = 0.1;

        $I->sendPATCH('apples/' . $appleId . '/' . $eatPercent);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'This apple is unripe yet']);
    }

    /**
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesEatRottenApi(ApiTester $I)
    {
        $I->wantToTest('to UPDATE rotten apple');
        $I->amBearerAuthenticated('tester-token');

        $appleId = 4;
        $eatPercent = 0.1;

        $I->sendPATCH('apples/' . $appleId . '/' . $eatPercent);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'This apple has already rotted']);
    }
}
