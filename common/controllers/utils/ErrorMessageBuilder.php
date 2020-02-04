<?php

namespace common\controllers\utils;

/**
 * Class ErrorMessageBuilder
 * @package common\controllers\utils
 * @deprecated Use common\domain\utils\ErrorMessageBuilder
 */
class ErrorMessageBuilder
{
    public static function build(array $errors, string $separator = "\n"): string
    {
        if (empty($errors)) {
            return '';
        }

        /**
         * @var string[] $propertyErrors
         */
        $messages = [];
        foreach ($errors as $propertyName => $propertyErrors) {
            $messages[] = join($propertyErrors, $separator);
        }

        return join($separator, $messages);
    }
}
