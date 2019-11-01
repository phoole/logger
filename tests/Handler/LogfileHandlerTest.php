<?php

declare(strict_types = 1);

namespace Phoole\Tests;

use PHPUnit\Framework\TestCase;
use Phoole\Logger\Entry\LogEntry;
use Phoole\Logger\Handler\LogfileHandler;

class LogfileHandlerTest extends TestCase
{
    private $file;

    private $obj;

    private $ref;

    /**
     * @covers Phoole\Logger\Handler\LogfileHandler::__construct()
     */
    public function testConstruct()
    {
        $this->assertTrue(file_exists($this->file));
    }

    /**
     * @covers Phoole\Logger\Handler\LogfileHandler::doRotation()
     */
    public function testDoRotation()
    {
        $file = sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'rotateFile';
        $time = time() - 86400;
        touch($file, $time);
        $r = $this->invokeMethod('doRotation', [$file, LogfileHandler::ROTATE_DATE]);
        $this->assertTrue($r);

        $new = $file . '_' . date('Ymd', $time);
        $this->assertTrue(file_exists($new));
        unlink($new);
    }

    protected function invokeMethod($methodName, array $parameters = array())
    {
        $method = $this->ref->getMethod($methodName);
        $method->setAccessible(TRUE);
        return $method->invokeArgs($this->obj, $parameters);
    }

    /**
     * @covers Phoole\Logger\Handler\LogfileHandler::handle()
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
        $this->file = sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'logfileTest';
        $this->obj = new LogfileHandler($this->file);
        $this->ref = new \ReflectionClass(get_class($this->obj));
    }

    protected function tearDown(): void
    {
        unlink($this->file);
        $this->obj = $this->ref = NULL;
        parent::tearDown();
    }
}