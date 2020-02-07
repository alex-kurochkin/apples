<?php
namespace api\tests\unit;

class SystemTest extends \Codeception\Test\Unit
{

    public function testPhpTimezone()
    {
        $this->assertEquals('UTC', date_default_timezone_get(), 'You PHP server and CLI must set timezone to UTC');
    }
}
