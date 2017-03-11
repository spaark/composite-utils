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

namespace Spaark\CompositeUtils\Test\Model;

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Model\ClassName;

/**
 * Do something
 */
class ClassNameTest extends TestCase
{
    public function testClassNameWithNamespace()
    {
        $classname = new ClassName('Namespace\Name\ClassName');

        $this->assertSame('Namespace\Name', $classname->namespace);
        $this->assertSame('ClassName', $classname->classname);
    }

    public function testClassNameWithoutNamespace()
    {
        $classname = new ClassName('ClassName');

        $this->assertSame('', $classname->namespace);
        $this->assertSame('ClassName', $classname->classname);
    }
}
