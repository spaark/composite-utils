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
 * @author Emily Shepherd <emily@emilyshepherd>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Traits;

use Spaark\CompositeUtils\Service\PropertyAccessor;

/**
 * Classes with this trait will be able to auto construct their
 * properties
 */
trait AutoConstructTrait
{
    use HasReflectorTrait;

    /**
     * Creates a new instance of this class, auto constructing the
     * properties using a PropertyAccessor
     */
    public function __construct(...$args)
    {
        $this->autoBuild(...$args);
    }

    /**
     * Creates a new instance of this class, auto constructing the
     * properties using a PropertyAccessor
     */
    protected function autoBuild(...$args)
    {
        (new PropertyAccessor($this, static::getReflectionComposite()))
            ->constructObject(...$args);
    }
}

