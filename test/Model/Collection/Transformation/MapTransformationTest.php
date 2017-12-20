<?php
/**
 * This file is part of the Composite Utils package.
 *
 * (c) Emily Shepherd <emily@emilyshepherd.me>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package spaark/composite-utils
 * @author Emily Shepherd <emily@emilyshepherd.me>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Test\Model\Collection\Transformation;

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Model\Collection\ListCollection\FixedList;
use Spaark\CompositeUtils\Model\Collection\Transformation\MapTransformation;

/**
 * Tests that the MapTransformation behaves in the way one would expect
 */
class MapTransformationTest extends TestCase
{
    /**
     * Sets up a map transformation and iterates over it, checking that
     * the results are as we would expect
     */
    public function testMap()
    {
        $collection = new FixedList(3);
        $collection[] = 1;
        $collection[] = 2;
        $collection[] = 3;

        $expect = new FixedList(3);
        $expect[] = 2;
        $expect[] = 4;
        $expect[] = 6;

        $trans = new MapTransformation(
            $collection,
            function($item) {
                return $item * 2;
            }
        );

        foreach ($trans as $i => $item) {
            $this->assertEquals($expect[$i], $item);
        }

        // Make sure an iteration actually happened
        $this->assertEquals(2, $i);
    }
}
