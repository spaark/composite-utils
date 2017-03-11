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

namespace Spaark\CompositeUtils\Test\Service;

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Service\ReflectionCompositeProvider;
use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Test\Model\TestEntity;

class ReflectionCompositeProviderTest extends TestCase
{
    public function testGetDefault()
    {
        $this->assertInstanceOf
        (
            ReflectionCompositeProvider::class,
            ReflectionCompositeProvider::getDefault()
        );
    }

    /**
     * @depends testGetDefault
     */
    public function testSetDefault()
    {
        $provider = new DummyReflectionCompositeProvider();

        ReflectionCompositeProvider::setDefault($provider);

        $this->assertSame
        (
            $provider,
            ReflectionCompositeProvider::getDefault()
        );
    }

    public function testProviderCache()
    {
        $provider = new ReflectionCompositeProvider();

        $this->assertSame
        (
            $provider->get(TestEntity::class),
            $provider->get(TestEntity::class)
        );
    }
}
