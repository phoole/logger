<?php

declare(strict_types = 1);

namespace Phoole\Tests;

use PHPUnit\Framework\TestCase;
use Phoole\Logger\Entry\LogEntry;
use Phoole\Logger\Handler\StreamHandler;

class StreamHandlerTest extends TestCase
{
    private $file;

    private $obj;

    private $ref;

    /**
     * @covers Phoole\Logger\Handler\StreamHandler::__construct()
     */
    public function testConstruct()
    {
        $this->assertTrue(file_exists($this->file));
    }

    /**
     * @covers Phoole\Logger\Handler\StreamHandler::handle()
     */
    public function testHandle()
    {
        $m = new LogEntry('test');
        $this->obj->handle($m);
        $this->assertEquals(
            'test',
            trim(file_get_contents($this->file))
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->file = sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'streamTest';
        $this->obj = new StreamHandler($this->file);
        $this->ref = new \ReflectionClass(get_class($this->obj));
    }

    protected function tearDown(): void
    {
        unlink($this->file);
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