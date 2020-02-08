<?php
declare(strict_types=1);

namespace api\controllers\actions\apples;

use common\domain\AppContext;
use api\models\apple\services\AppleService;
use common\controllers\dtos\ObjectResponseDto;
use common\domain\utils\DateTimes;
use yii\base\Action;
use yii\base\Controller;
use yii\web\BadRequestHttpException;

class FallAction extends Action
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
     * @return ObjectResponseDto
     * @throws BadRequestHttpException
     */
    public function run(int $id)
    {
        $userId = $this->appContext->getUserId();

        try {
            $apple = $this->appleService->findOneByIdAndUserId($id, $userId);

            if($apple->fallenAt) {
                throw new \LogicException('This apple is already fallen');
            }

            $now = new \DateTime();
            $apple->fallenAt = $now;

            $this->appleService->updateOne($apple);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new ObjectResponseDto($now->format(DateTimes::ISO_FORMAT));
    }
}
