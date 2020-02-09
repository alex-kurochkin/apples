<?php
declare(strict_types=1);

namespace api\controllers\actions\apples;

use common\domain\AppContext;
use api\models\apple\services\AppleService;
use common\controllers\dtos\ObjectResponseDto;
use Exception;
use yii\base\Action;
use yii\base\Controller;
use yii\web\BadRequestHttpException;

class CreateAction extends Action
{

    /**
     * @var AppContext
     */
    private $appContext;

    /**
     * @var AppleService
     */
    private $appleService;

    /**
     * GetApiSettingsAction constructor.
     * @param $id
     * @param Controller $controller
     * @param AppContext $context
     * @param AppleService $appleService
     * @param array $config
     */
    public function __construct(
        $id,
        Controller $controller,
        AppContext $context,
        AppleService $appleService,
        $config = []
    ) {
        $this->appContext = $context;
        $this->appleService = $appleService;

        parent::__construct($id, $controller, $config);
    }

    /**
     * @return ObjectResponseDto
     * @throws BadRequestHttpException
     */
    public function run()
    {
        $count = rand(4, 10);
        $userId = $this->appContext->getUserId();

        try {
            $this->appleService->createMany($userId, $count);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new ObjectResponseDto($count);
    }
}
