<?php
declare(strict_types=1);

namespace api\models\apple;

class AppleColor
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
    public $color;
}
