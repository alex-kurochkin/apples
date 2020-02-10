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
use StdClass;

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

        $I->sendDELETE('apple/100');
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

        $I->sendDELETE('apple/1');
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
    public function testApplesEatMissedPercentApi(ApiTester $I)
    {
        $I->wantToTest('to UPDATE - eat apple missed percent');
        $I->amBearerAuthenticated('tester-token');

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH(
            'apple/1',
            [
                'eatPercentPrecision' => 2,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Eaten Percent cannot be blank.']);
    }

    /**
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesEatMissedPercentPrecisionApi(ApiTester $I)
    {
        $I->wantToTest('to UPDATE - eat apple missed percent precision');
        $I->amBearerAuthenticated('tester-token');

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH(
            'apple/1',
            [
                'eatenPercent' => .5,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Eat Percent Precision cannot be blank.']);
    }

    /**
     * @depends testApplesEatMissedPercentApi
     * @depends testApplesEatMissedPercentPrecisionApi
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesEatApi(ApiTester $I)
    {
        $I->wantToTest('to UPDATE - eat apple');
        $I->amBearerAuthenticated('tester-token');

        $appleId = 2;
        $eatPercent = 0.15;

        $percentBefore = $this->getEatenPercent($I, $appleId);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH(
            'apple/' . $appleId,
            [
                'eatenPercent' => $eatPercent,
                'eatPercentPrecision' => 2,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);

        $percentAfter = $this->getEatenPercent($I, $appleId);

        $I->assertEquals($percentBefore + $eatPercent, $percentAfter);
    }

    /**
     * @depends testApplesEatMissedPercentApi
     * @depends testApplesEatMissedPercentPrecisionApi
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

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('apple/' . $appleId,
            [
                'eatenPercent' => $eatPercent,
                'eatPercentPrecision' => 2,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);
    }

    /**
     * @depends testApplesEatMissedPercentApi
     * @depends testApplesEatMissedPercentPrecisionApi
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesEatUncreatedApi(ApiTester $I)
    {
        $I->wantToTest('to UPDATE eat uncreated apple');
        $I->amBearerAuthenticated('tester-token');

        $appleId = 100;
        $eatPercent = 0.1;

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('apple/' . $appleId,
            [
                'eatenPercent' => $eatPercent,
                'eatPercentPrecision' => 2,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Not found.']);
    }

    /**
     * @depends testApplesEatMissedPercentApi
     * @depends testApplesEatMissedPercentPrecisionApi
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

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('apple/' . $appleId,
            [
                'eatenPercent' => $eatPercent,
                'eatPercentPrecision' => 2,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Eaten Percent must be no greater than 1.']);
    }

    /**
     * @depends testApplesEatMissedPercentApi
     * @depends testApplesEatMissedPercentPrecisionApi
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

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('apple/' . $appleId,
            [
                'eatenPercent' => $eatPercent,
                'eatPercentPrecision' => 2,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'You are trying to bite off more than the remaining apple']);
    }

    /**
     * @depends testApplesEatMissedPercentApi
     * @depends testApplesEatMissedPercentPrecisionApi
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesEatPrecisionApi(ApiTester $I)
    {
        $I->wantToTest('to UPDATE apple check precision');
        $I->amBearerAuthenticated('tester-token');

        $appleId = 1;
        $eatPercent1 = 0.1;

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('apple/' . $appleId,
            [
                'eatenPercent' => $eatPercent1,
                'eatPercentPrecision' => 1,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);

        $percentAfter1 = (string)$this->getEatenPercent($I, $appleId);
        $I->assertEquals('0.1', $percentAfter1);

        $eatPercent2 = 0.2;

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('apple/' . $appleId,
            [
                'eatenPercent' => $eatPercent2,
                'eatPercentPrecision' => 1,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);

        $percentAfter2 = (string)$this->getEatenPercent($I, $appleId);
        $I->assertEquals('0.3', $percentAfter2);
    }

    /**
     * @depends testApplesEatMissedPercentApi
     * @depends testApplesEatMissedPercentPrecisionApi
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

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('apple/' . $appleId,
            [
                'eatenPercent' => $eatPercent,
                'eatPercentPrecision' => 2,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'This apple is unripe yet']);
    }

    /**
     * @depends testApplesEatMissedPercentApi
     * @depends testApplesEatMissedPercentPrecisionApi
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

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('apple/' . $appleId,
            [
                'eatenPercent' => $eatPercent,
                'eatPercentPrecision' => 2,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'This apple has already rotted']);
    }

    /**
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesFallApi(ApiTester $I)
    {
        $I->wantToTest('to PUT to fall apple');
        $I->amBearerAuthenticated('tester-token');

        $appleId = 3;

        $I->sendPUT('apple/' . $appleId);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);

        $I->sendGET('apples');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['result' => 'success']);
        $apples = $I->grabDataFromResponseByJsonPath('$.data.apples')[0];

        $apple = $this->findApple($apples, $appleId);

        $I->assertEquals(true, (bool)$apple->fallenAt);
    }

    /**
     * @depends testApplesFallApi
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesFallTwiceApi(ApiTester $I)
    {
        $I->wantToTest('to PUT to fall apple twice');
        $I->amBearerAuthenticated('tester-token');

        $appleId = 3;

        $I->sendPUT('apple/' . $appleId);
        $I->sendPUT('apple/' . $appleId);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'This apple is already fallen']);
    }

    /**
     * @param ApiTester $I
     * @group ApplesAPI
     * @throws Exception
     */
    public function testApplesFallUncreatedApi(ApiTester $I)
    {
        $I->wantToTest('to PUT to fall uncreated apple');
        $I->amBearerAuthenticated('tester-token');

        $I->sendPUT('apple/100');
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Not found.']);
    }

    //// SERVICE FUNCTIONS ////

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
        return $this->findApple($apples, $appleId)->eatenPercent;
    }

    /**
     * @param array $apples
     * @param int $appleId
     * @return StdClass
     * @throws ErrorException
     */
    private function findApple(array $apples, int $appleId): StdClass
    {
        foreach ($apples as $apple) {
            $apple = (object)$apple;
            if ($appleId === $apple->id) {
                return $apple;
            }
        }

        throw new ErrorException('Apple with id' . $appleId . ' not found');
    }
}
