<?php

declare(strict_types=1);

namespace Phoole\Tests;

use PHPUnit\Framework\TestCase;
use Phoole\Logger\Entry\MemoryInfo;

class MemoryInfoTest extends TestCase
{
    private $obj;

    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new MemoryInfo();
        $this->ref = new \ReflectionClass(get_class($this->obj));
    }

    protected function tearDown(): void
    {
        $this->obj = $this->ref = NULL;
        parent::tearDown();
    }

    protected function invokeMethod(object $object, $methodName, array $parameters = array())
    {
        $ref = new \ReflectionClass(get_class($object));
        $method = $ref->getMethod($methodName);
        $method->setAccessible(TRUE);
        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @covers Phoole\Logger\Entry\MemoryInfo::process()
     */
    public function testProcess()
    {
        $this->obj->process();
        $queue = $this->invokeMethod($this->obj, 'getProcessors');
        $this->assertEquals(1, count($queue));

        $context = $this->obj->getContext();
        $this->assertTrue(isset($context['memory_used']));

        $this->expectOutputRegex('/peak usage/');
        echo (string) $this->obj;
    }
}