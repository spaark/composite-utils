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

namespace Spaark\CompositeUtils\Service;

use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;

/**
 */
interface ReflectionCompositeProviderInterface
{
    /**
     * Returns a ReflectionComposite
     *
     * @param string $classname
     * @return ReflectionComposite
     */
    public function get(string $classname) : ReflectionComposite;
}
