<?php
declare(strict_types=1);

namespace api\controllers;

use api\controllers\actions\apples\CreateAction;
use api\controllers\actions\apples\DeleteAction;
use api\controllers\actions\apples\EatAction;
use api\controllers\actions\apples\FallAction;
use api\controllers\actions\apples\ListAction;
use common\components\RestController;
use common\domain\AppConfig;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\filters\VerbFilter;
use yii\web\ErrorAction;

/**
 * Apples controller
 */
class ApplesController extends RestController
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
            'class' => Cors::class,
            'cors' => [
                'Origin' => [AppConfig::getCorsOrigin()],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['Content-Type', 'Authorization'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
            ],
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'list' => ['GET'],
                'create' => ['POST'],
                'eat' => ['PATCH'],
                'fall' => ['PUT'],
                'delete' => ['DELETE'],
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
            'fall' => [
                'class' => FallAction::class,
            ],
            'eat' => [
                'class' => EatAction::class,
            ],
            'delete' => [
                'class' => DeleteAction::class,
            ],
        ];
    }
}
