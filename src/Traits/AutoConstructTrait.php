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

trait AutoConstructTrait
{
    use HasReflectorTrait;

    public function __construct(...$args)
    {
        $this->autoBuild(...$args);
    }

    protected function autoBuild(...$args)
    {
        (new PropertyAccessor($this, static::getReflectionComposite()))
            ->constructObject(...$args);
    }
}

