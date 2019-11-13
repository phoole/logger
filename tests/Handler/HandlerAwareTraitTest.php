<?php

declare(strict_types=1);

namespace Phoole\Tests;

use Psr\Log\LogLevel;
use PHPUnit\Framework\TestCase;
use Phoole\Logger\Entry\LogEntry;
use Phoole\Logger\Entry\MemoryInfo;
use Phoole\Logger\Handler\LogfileHandler;
use Phoole\Logger\Handler\HandlerAwareTrait;
use Phoole\Logger\Handler\HandlerAwareInterface;

class myHandlerAware implements HandlerAwareInterface
{
    use HandlerAwareTrait;
}

class HandlerAwareTraitTest extends TestCase
{
    private $file;

    private $file2;

    private $obj;

    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->file = sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'handlerAware';
        $this->file2 = sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'handlerAware2';
        $this->obj = new myHandlerAware();
        $this->ref = new \ReflectionClass(get_class($this->obj));
    }

    protected function tearDown(): void
    {
        if (\file_exists($this->file)) {
            unlink($this->file);
        }
        if (\file_exists($this->file2)) {
            unlink($this->file2);
        }
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
     * @covers Phoole\Logger\Handler\HandlerAwareTrait::addHandler()
     */
    public function testAddHandler()
    {
        $filelog = new LogfileHandler($this->file);
        $this->obj->addHandler(LogLevel::ERROR, $filelog);

        $m = (new LogEntry())->setLevel(LogLevel::INFO);
        $h = $this->invokeMethod('getHandlers', [$m]);
        $this->assertEquals(0, count($h));

        $m = (new LogEntry())->setLevel(LogLevel::ERROR);
        $h = $this->invokeMethod('getHandlers', [$m]);
        $this->assertEquals(1, count($h));
    }

    /**
     * @covers Phoole\Logger\Handler\HandlerAwareTrait::getHandlers()
     */
    public function testGetHandlers()
    {
        $file = new LogfileHandler($this->file);
        $file2 = new LogfileHandler($this->file2);

        $this->obj->addHandler(LogLevel::INFO, $file);

        $this->obj->addHandler(
            LogLevel::ERROR,
            $file2,
            MemoryInfo::class
        );

        $m = (new LogEntry())->setLevel(LogLevel::ALERT);
        $h = $this->invokeMethod('getHandlers', [$m]);
        $this->assertEquals(1, count($h));

        $m = (new MemoryInfo())->setLevel(LogLevel::ALERT);
        $h = $this->invokeMethod('getHandlers', [$m]);
        $this->assertEquals(2, count($h));
    }
}