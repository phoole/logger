<?php

declare(strict_types = 1);

namespace Phoole\Tests;

use Psr\Log\LogLevel;
use Phoole\Logger\Logger;
use PHPUnit\Framework\TestCase;
use Phoole\Logger\Entry\SystemLog;
use Phoole\Logger\Handler\EchoHandler;

class LoggerTest extends TestCase
{
    private $obj;

    private $ref;

    /**
     * @covers Phoole\Logger\Logger::log()
     */
    public function testLog()
    {
        $this->obj->addHandler(
            new EchoHandler(),
            LogLevel::INFO
        );
        $this->expectOutputRegex('/test.*peak.*peak.*/s');

        $this->obj->info('test');

        $this->obj->addHandler(
            new EchoHandler(),
            LogLevel::ALERT,
            SystemLog::class
        );

        $this->obj->alert(new SystemLog());
        $this->assertTrue(TRUE);
    }

    /**
     * @covers Phoole\Logger\Logger::log()
     */
    public function testLog2()
    {
        $this->expectExceptionMessage('unknown log');
        $this->obj->log('test', 'test');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new Logger('LOG');
        $this->ref = new \ReflectionClass(get_class($this->obj));
    }

    protected function tearDown(): void
    {
        $this->obj = $this->ref = NULL;
        parent::tearDown();
    }

    protected function invokeMethod($methodName, array $parameters = array())
    {
        $method = $this->ref->getMethod($methodName);
        $method->setAccessible(TRUE);
        return $method->invokeArgs($this->obj, $parameters);
    }
}