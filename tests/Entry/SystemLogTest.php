<?php

declare(strict_types=1);

namespace Phoole\Tests;

use PHPUnit\Framework\TestCase;
use Phoole\Logger\Entry\SystemLog;
use Phoole\Logger\Processor\MemoryProcessor;

class SystemLogTest extends TestCase
{
    private $obj;
    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new SystemLog();
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
     * @covers Phoole\Logger\Entry\LogEntry::getProcessors()
     */
    public function testGetProcessors()
    {
        $a = $this->obj->getProcessors();
        $this->assertTrue(1 === count($a));
        $this->assertEquals(
            MemoryProcessor::class,
            $a[0]
        );
    }
}