<?php
/**
 * This file is part of the Composite Utils package.
 *
 * (c) Emily Shepherd <emily@emilyshepherd.me>
 *
 * For the full copyright and licence information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package spaark/composite-utils
 * @author Emily Shepherd <emily@emilyshepherd>
 * @license MIT
 */

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
