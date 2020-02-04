<?php

namespace common\domain;

use DateTimeZone;
use Exception;
use InvalidArgumentException;

class DateTime extends \DateTime
{
    /**
     * DateTime constructor.
     * @param string $time
     * @param DateTimeZone|null $timezone
     */
    public function __construct(string $time = 'now', DateTimeZone $timezone = null)
    {
        try {
            parent::__construct($time, $timezone);
        } catch (Exception $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode());
        }

        $errors = \DateTime::getLastErrors();
        foreach ($errors['warnings'] as $warning) {
            throw new InvalidArgumentException($warning);
        }

        foreach ($errors['errors'] as $error) {
            throw new InvalidArgumentException($error);
        }
    }

}
