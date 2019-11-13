<?php

declare(strict_types=1);

namespace Phoole\Tests;

use PHPUnit\Framework\TestCase;
use Phoole\Logger\Entry\LogEntry;
use Phoole\Logger\Formatter\DefaultFormatter;

class DefaultFormatterTest extends TestCase
{
    private $obj;

    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new DefaultFormatter();
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
     * @covers Phoole\Logger\Formatter\DefaultFormatter::format()
     */
    public function testFormatter()
    {
        $m = new LogEntry('test {wow}', ['__channel' => 'PHOOLE', 'wow' => 'bingo']);
        $s = $this->obj->format($m);
        $this->assertEquals(
            '[PHOOLE] INFO: test bingo',
            trim($s)
        );
    }
}