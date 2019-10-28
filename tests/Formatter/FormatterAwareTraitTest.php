<?php

declare(strict_types=1);

namespace Phoole\Tests;

use PHPUnit\Framework\TestCase;
use Phoole\Logger\Formatter\DefaultFormatter;
use Phoole\Logger\Formatter\FormatterAwareTrait;
use Phoole\Logger\Formatter\FormatterAwareInterface;
use Phoole\Logger\Entry\LogEntry;

class myClass implements FormatterAwareInterface
{
    use FormatterAwareTrait;
}

class FormatterAwareTraitTest extends TestCase
{
    private $obj;
    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new myClass();
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
     * @covers Phoole\Logger\Formatter\FormatterAwareTrait::setFormatter()
     */
    public function testSetFormatter()
    {
        $f = new DefaultFormatter;
        $this->obj->setFormatter($f);
        $this->assertTrue($f === $this->obj->getFormatter());
    }

    /**
     * @covers Phoole\Logger\Formatter\FormatterAwareTrait::getFormatter()
     */
    public function testGetFormatter()
    {
        $this->expectExceptionMessage('null');
        $this->obj->getFormatter();
    }
}