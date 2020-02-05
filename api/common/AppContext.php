<?php
declare(strict_types=1);

namespace api\common;

use Yii;
use yii\web\User;

class AppContext
{

    public function getUser(): ?User
    {
        return Yii::$app->getUser();
    }

    public function getUserId(): ?int
    {
        $user = $this->getUser();

        return $user ? $user->getId() : null;
    }

    public function getUserIp(): string
    {
        return (string)Yii::$app->getRequest()->getUserIP();
    }
}
