<?php
declare(strict_types=1);

namespace api\controllers\actions\apples;

use api\common\AppContext;
use api\models\apple\services\AppleService;
use common\controllers\dtos\ObjectResponseDto;
use yii\base\Action;
use yii\base\Controller;

class DeleteAction extends Action
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
     * @param $id
     * @return ObjectResponseDto
     */
    public function run(int $id)
    {
        $userId = $this->appContext->getUserId();

        $this->appleService->deleteOne($userId, $id);

        return new ObjectResponseDto(time());
    }
}
