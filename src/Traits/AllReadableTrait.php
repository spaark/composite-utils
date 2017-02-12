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

use Spaark\CompositeUtils\Service\RawPropertyAccessor;

trait AllReadableTrait
{
    /**
     * @var RawPropertyAccessor
     */
    protected $accessor;

    protected function getPropertyAccessor()
    {
        if (!$this->accessor)
        {
            $this->accessor = new RawPropertyAccessor($this);
        }

        return $this->accessor;
    }

    public function __get($property)
    {
        return $this->getPropertyAccessor()->getRawValue($property);
    }
}
