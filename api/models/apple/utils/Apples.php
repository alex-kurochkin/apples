<?php
declare(strict_types=1);

namespace api\models\apple\utils;

use common\domain\AppConfig;
use api\models\apple\Apple;
use DateTime;
use Exception;
use LogicException;

class Apples
{

    /**
     * @param Apple $apple
     * @param float $percent
     * @throws Exception
     */
    public static function checkEatPossibility(Apple $apple, float $percent)
    {
        if (!$apple->fallenAt) {
            throw new LogicException('This apple is unripe yet');
        }

        if (!self::detectFresh($apple->fallenAt)) {
            throw new LogicException('This apple has already rotted');
        }

        if (AppConfig::getAppleEatPartPossibility() <= $apple->eatenPercent) {
            throw new LogicException('This apple has already been eaten');
        }

        if (1 < $apple->eatenPercent + $percent) {
            throw new LogicException('You are trying to bite off more than the remaining apple');
        }
    }

    /**
     * @param DateTime $fallenAt
     * @return bool true - fresh, false - rotten
     * @throws Exception
     */
    public static function detectFresh(DateTime $fallenAt): bool
    {
        $now = new DateTime();
        $freshDuration = AppConfig::getAppleFreshDuration(); // hours
        return $now < $fallenAt->modify('+' . $freshDuration . ' hour');
    }
}
