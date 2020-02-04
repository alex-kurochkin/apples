<?php

namespace common\domain;

class AppConfig
{

    public function getMaxEmailErrors(): int
    {
        return env('EMAIL_ERRORS_ALLOWED', 3);
    }

    public function getMaxSslCertificateErrors(): int
    {
        return env('SSL_CERTIFICATE_ERRORS_ALLOWED', 7 * 24 * 12); // one week for 5 min interval
    }
    /**
     * @return string
     */
    public function getPasswordSalt()
    {
        return \Yii::$app->params['passwordSalt'];
    }

    /**
     * Get tow factor authorization secret salt
     * @return string
     */
    public function getTfaSecretSalt()
    {
        return \Yii::$app->params['tfaSecretSalt'];
    }
}
