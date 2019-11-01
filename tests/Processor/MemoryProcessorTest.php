<?php

declare(strict_types = 1);

namespace Phoole\Tests;

use PHPUnit\Framework\TestCase;
use Phoole\Logger\Entry\LogEntry;
use Phoole\Logger\Processor\MemoryProcessor;

class MemoryProcessorTest extends TestCase
{
    private $obj;

    private $ref;

    /**
     * @covers Phoole\Logger\Processor\MemoryProcessor::process()
     */
    public function testProcess()
    {
        $m = new LogEntry('test', ['a' => 'a']);
        $this->obj->process($m);
        $b = $m->getContext();
        $this->assertEquals(3, count($b));
        $this->assertTrue(isset($b['memory_used']));
        $this->assertTrue(isset($b['memory_peak']));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new MemoryProcessor();
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