<?php

declare(strict_types=1);

namespace Phoole\Tests;

use PHPUnit\Framework\TestCase;
use Phoole\Logger\Processor\ProcessorAwareTrait;
use Phoole\Logger\Processor\ProcessorAwareInterface;

class A implements ProcessorAwareInterface
{
    use ProcessorAwareTrait;

    protected static function classProcessors(): array
    {
        self::$parameterClass = A::class;
        return [
            function(A $entry) {
                echo 'inA';
            }
        ];
    }
}

class AA extends A
{
    protected static function classProcessors(): array
    {
        self::$parameterClass = A::class;
        return [
            function(A $entry) {
                echo 'inAA';
            }
        ];
    }
}

class ProcessorAwareTraitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    protected function invokeMethod(object $object, $methodName, array $parameters = array())
    {
        $ref = new \ReflectionClass(get_class($object));
        $method = $ref->getMethod($methodName);
        $method->setAccessible(TRUE);
        return $method->invokeArgs($object, $parameters);
    }

    protected function getPrivateProperty($obj, $propertyName)
    {
        $ref = new \ReflectionClass(get_class($obj));
        $property = $ref->getProperty($propertyName);
        $property->setAccessible(TRUE);
        return $property->getValue($obj);
    }

    /**
     * @covers Phoole\Logger\Entry\ProcessorAwareTrait::initProcessors()
     */
    public function testInitProcessors()
    {
        $obj = new AA();
        $res = $this->getPrivateProperty($obj, 'processors');
        $this->assertEquals(0, count($res));

        $this->invokeMethod($obj, 'initProcessors');
        $res = $this->getPrivateProperty($obj, 'processors');
        $this->assertEquals(1, count($res));

        $obj = new A();
        $this->invokeMethod($obj, 'initProcessors');
        $res = $this->getPrivateProperty($obj, 'processors');
        $this->assertEquals(2, count($res));
    }

    /**
     * @covers Phoole\Logger\Entry\ProcessorAwareTrait::process()
     */
    public function testProcess()
    {
        $aa = new AA();

        $this->expectOutputString('inAinAA');
        $aa->process();
    }

    /**
     * @covers Phoole\Logger\Entry\ProcessorAwareTrait::getClassTree()
     */
    public function testGetClassTree()
    {
        $obj = new AA();
        $this->assertEquals(
            [
                A::class,
                AA::class
            ],
            $this->invokeMethod($obj, 'getClassTree')
        );
    }

    /**
     * @covers Phoole\Logger\Entry\ProcessorAwareTrait::classProcessors()
     */
    public function testClassProcessors()
    {
        $obj = new AA();
        $res = $this->invokeMethod($obj, 'classProcessors');
        $this->assertEquals(1, count($res));

        $obj = new A();
        $res = $this->invokeMethod($obj, 'classProcessors');
        $this->assertEquals(1, count($res));
    }

    /**
     * @covers Phoole\Logger\Entry\ProcessorAwareTrait::GetProcessors()
     */
    public function testGetProcessors()
    {
        $obj = new AA();
        $res = $this->invokeMethod($obj, 'getProcessors');
        $this->assertEquals(2, count($res));
    }

    /**
     * @covers Phoole\Logger\Entry\ProcessorAwareTrait::addProcessor()
     */
    public function testAddProcessor()
    {
        $obj = new AA();
        $this->invokeMethod($obj, 'getProcessors');
        $res = $this->getPrivateProperty($obj, 'processors');
        $this->assertEquals(1, count($res[AA::class]));

        $this->invokeMethod(
            $obj, 'addProcessor', [
            function(A $bingo) {
                echo 'bingo';
            }
        ]
        );
        $res = $this->getPrivateProperty($obj, 'processors');
        $this->assertEquals(2, count($res[AA::class]));
    }
}