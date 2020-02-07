<?php
declare(strict_types=1);

namespace api\controllers\actions\apples\params;

use common\controllers\params\Params;

class EatParams extends Params
{

    /**
     * @var float
     */
    public $eatenPercent;

    public function rules(): array
    {
        return [
            ['eatenPercent', 'double', 'min' => 0, 'max' => 1],
            [['eatenPercent'], 'required'],
        ];
    }
}
