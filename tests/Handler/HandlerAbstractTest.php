<?php

declare(strict_types=1);

namespace Phoole\Tests;

use PHPUnit\Framework\TestCase;
use Phoole\Logger\Entry\LogEntry;
use Phoole\Logger\Handler\HandlerAbstract;
use Phoole\Logger\Formatter\AnsiFormatter;
use Phoole\Logger\Entry\LogEntryInterface;
use Phoole\Logger\Formatter\DefaultFormatter;

class myHandler extends HandlerAbstract
{
    protected function write(LogEntryInterface $entry)
    {
        echo $entry;
    }
}

class HandlerAbstractTest extends TestCase
{
    private $obj;

    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new myHandler();
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

    /**
     * @covers Phoole\Logger\Handler\HandlerAbstract::__construct()
     */
    public function testConstruct()
    {
        $this->assertTrue($this->obj->getFormatter() instanceof DefaultFormatter);

        $obj = new myHandler(new AnsiFormatter());
        $this->assertTrue($obj->getFormatter() instanceof AnsiFormatter);
    }

    /**
     * @covers Phoole\Logger\Handler\HandlerAbstract::__invoke()
     */
    public function testInvoke()
    {
        $this->expectOutputString('test');
        $handler = $this->obj;
        $handler(new LogEntry('test'));
    }
}