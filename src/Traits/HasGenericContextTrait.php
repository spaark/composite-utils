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

namespace Spaark\CompositeUtils\Traits;

use Spaark\CompositeUtils\Model\Generic\GenericContext;
use Spaark\CompositeUtils\Model\Reflection\Type\AbstractType;
use Spaark\CompositeUtils\Model\Reflection\Type\GenericType;
use Spaark\CompositeUtils\Exception\MissingContextException;

/**
 * Do something
 */
trait HasGenericContextTrait
{
    /**
     * @var GenericContext
     */
    protected $context;

    public function __construct(GenericContext $context = null)
    {
        $this->context = $context;
    }

    protected function getGenericType(GenericType $generic)
        : AbstractType
    {
        if (!$this->context)
        {
            throw new MissingContextException();
        }

        return $this->context->getGenericType($generic->name);
    }
}
