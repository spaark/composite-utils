<?php

namespace Spaark\CompositeUtils\Test\Traits;

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Test\Model\AllReadableComposite;

class AllReadableTraitTest extends TestCase
{
    /**
     * @dataProvider propertiesProvider
     */
    public function testRead(string $property, $expectedValue)
    {
        $testComposite = new AllReadableComposite();

        $this->assertEquals($expectedValue, $testComposite->$property);
    }

    /**
     * @expectedException Spaark\CompositeUtils\Exception\CannotReadPropertyException
     */
    public function testImpossibleRead()
    {
        (new AllReadableComposite())->nonexistant;
    }

    public function propertiesProvider()
    {
        return
        [
            ['a', '123'],
            ['b', 123],
            ['c', true]
        ];
    }
}
