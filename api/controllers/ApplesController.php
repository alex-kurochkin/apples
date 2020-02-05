<?php
declare(strict_types=1);

namespace api\controllers;

use api\controllers\actions\apples\CreateAction;
use api\controllers\actions\apples\ListAction;
use common\components\RestController;
use yii\filters\auth\HttpBearerAuth;
use yii\web\ErrorAction;

/**
 * Apples controller
 */
class ApplesController  extends RestController
{

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors'  => [
                'Origin' => ['http://apples.local'],
                'Access-Control-Request-Methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['Content-Type', 'Authorization'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
            ],
        ];

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'list' => [
                'class' => ListAction::class,
            ],
            'create' => [
                'class' => CreateAction::class,
            ],
//            'eat' => [
//                'class' => EatAction::class,
//            ],
        ];
    }
}
