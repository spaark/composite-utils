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

use Spaark\CompositeUtils\Service\ReflectionCompositeProviderInterface;
use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;

class DummyReflectionCompositeProvider
    implements ReflectionCompositeProviderInterface
{
    public function get(string $name) : ReflectionComposite
    {
        return new ReflectionComposite();
    }
}
