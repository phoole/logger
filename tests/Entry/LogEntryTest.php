<?php

declare(strict_types=1);

namespace Phoole\Tests;

use PHPUnit\Framework\TestCase;
use Phoole\Logger\Entry\LogEntry;

class LogEntryTest extends TestCase
{
    private $obj;
    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new LogEntry();
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
     * @covers Phoole\Logger\Entry\LogEntry::__construct()
     */
    public function testConstruct()
    {
        $obj = new LogEntry('new {msg}', ['msg' => 'message']);
        $this->assertEquals(
            'new message',
            (string) $obj
        );
    }

    /**
     * @covers Phoole\Logger\Entry\LogEntry::getMessage()
     */
    public function testGetMessage()
    {
        // default message
        $this->assertEquals('log message', $this->obj->getMessage());

        $obj = new LogEntry('test message');
        $this->assertEquals('test message', $obj->getMessage());
    }

    /**
     * @covers Phoole\Logger\Entry\LogEntry::getLevel()
     * @covers Phoole\Logger\Entry\LogEntry::setLevel()
     */
    public function testGetLevel()
    {
        // default message
        $this->assertEquals('', $this->obj->getLevel());

        $this->obj->setLevel('error');
        $this->assertEquals('error', $this->obj->getLevel());
    }

    /**
     * @covers Phoole\Logger\Entry\LogEntry::getContext()
     * @covers Phoole\Logger\Entry\LogEntry::setContext()
     */
    public function testGetContext()
    {
        // default message
        $this->assertEquals([], $this->obj->getContext());

        $c = ['a'];
        $this->obj->setContext($c);
        $this->assertEquals($c, $this->obj->getContext());
    }

    /**
     * @covers Phoole\Logger\Entry\LogEntry::getProcessors()
     */
    public function testGetProcessors()
    {
        $this->assertEquals([], $this->obj->getProcessors());
    }

    /**
     * @covers Phoole\Logger\Entry\LogEntry::__toString()
     */
    public function testToString()
    {
        // default
        $this->assertEquals('log message', (string) $this->obj);

        $obj = new LogEntry('{test} is new', ['test' => 'wow']);
        $this->assertEquals('wow is new', (string) $obj);
    }

    /**
     * @covers Phoole\Logger\Entry\LogEntry::interpolate()
     */
    public function testInterpolate()
    {
        $message = '{txt} is {bingo}';
        $context = ['txt' => 'string', 'bingo' => $this->obj];
        $this->assertEquals(
            'string is log message',
            $this->invokeMethod('interpolate', [$message, $context])
        );
    }
}