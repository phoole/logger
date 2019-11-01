<?php

declare(strict_types = 1);

namespace Phoole\Tests;

use PHPUnit\Framework\TestCase;
use Phoole\Logger\Entry\LogEntry;
use Phoole\Logger\Formatter\AnsiFormatter;

class AnsiFormatterTest extends TestCase
{
    private $obj;

    private $ref;

    /**
     * @covers Phoole\Logger\Formatter\AnsiFormatter::format()
     */
    public function testFormatter()
    {
        $m = new LogEntry('test {wow}', ['__channel' => 'PHOOLE', 'wow' => 'bingo']);
        $m->setLevel('error');
        $this->expectOutputRegex('/test bingo/');
        echo $this->obj->format($m);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new AnsiFormatter();
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