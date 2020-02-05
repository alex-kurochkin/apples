<?php
declare(strict_types=1);

namespace api\models\apple;

use DateTime;

class Apple
{

    const ID = 'id';

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $userId;

    /**
     * @var int
     */
    public $colorId;

    /**
     * @var DateTime
     */
    public $createdAt;

    /**
     * @var DateTime
     */
    public $fallenAt;

    /**
     * @var float
     */
    public $eatenPercent = 0;
}
