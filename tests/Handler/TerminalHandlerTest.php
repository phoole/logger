<?php

declare(strict_types=1);

namespace Phoole\Tests;

use PHPUnit\Framework\TestCase;
use Phoole\Logger\Handler\TerminalHandler;
use Phoole\Logger\Entry\LogEntry;
use Phoole\Logger\Entry\LogEntryInterface;

class TerminalHandlerTest extends TestCase
{
    private $obj;
    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new TerminalHandler();
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
     * @covers Phoole\Logger\Handler\TerminalHandler::__construct()
     */
    public function testConstruct()
    {
        $obj1 = new TerminalHandler('php://stdout');
        $this->expectExceptionMessage('unknown stream');
        $obj2 = new TerminalHandler('test');
    }
}