<?php
declare(strict_types=1);

namespace api\controllers\actions\apples;

use common\domain\AppContext;
use api\controllers\actions\apples\params\EatParams;
use api\models\apple\services\AppleService;
use api\models\apple\utils\Apples;
use common\controllers\dtos\ObjectResponseDto;
use common\domain\utils\ErrorMessageBuilder;
use LogicException;
use yii\base\Action;
use yii\base\Controller;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;

class EatAction extends Action
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
     * @param int $id
     * @param float $percent
     * @return ObjectResponseDto
     * @throws BadRequestHttpException
     * @throws LogicException
     */
    public function run(int $id, float $percent)
    {
        $params = new EatParams();

        $params->load(['eatenPercent' => $percent]);
        if (!$params->validate()) {
            throw new BadRequestHttpException(ErrorMessageBuilder::build($params->errors));
        }

        $userId = $this->appContext->getUserId();

        try {
            $apple = $this->appleService->findOneByIdAndUserId($id, $userId);

            Apples::checkEatPossibility($apple, $percent);

            $apple->eatenPercent += $percent;

            $this->appleService->updateOne($apple);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new ObjectResponseDto([$id, $percent, $apple]);
    }
}
