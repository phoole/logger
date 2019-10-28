<?php

declare(strict_types=1);

namespace Phoole\Tests;

use PHPUnit\Framework\TestCase;
use Phoole\Logger\Handler\EchoHandler;
use Phoole\Logger\Entry\LogEntry;
use Phoole\Logger\Entry\LogEntryInterface;

class EchoHandlerTest extends TestCase
{
    private $obj;
    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new EchoHandler();
        $this->ref = new \ReflectionClass(get_class($this->obj));
    }

    protected function tearDown(): void
    {
        $this->obj = $this->ref = null;
        parent::tearDown();
    }

    protected function invokeMethod($methodName, array $parameters = array())
    {
        $method = $this->ref->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($this->obj, $parameters);
    }

    /**
     * @covers Phoole\Logger\Handler\EchoHandler::handle()
     */
    public function testHandle()
    {
        $m = new LogEntry('test');
        $this->expectOutputRegex('/test/');
        $this->obj->handle($m);
    }
}