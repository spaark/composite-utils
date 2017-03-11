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

use Spaark\CompositeUtils\Model\Reflection\Type\AbstractType;

/**
 * Dummy AbstractType used for testing a DomainException is thrown when
 * using unknown data types
 */
class DummyType extends AbstractType
{
    public function equals($object) : bool
    {
        return false;
    }

    public function compatible(AbstractType $object) : bool
    {
        return false;
    }
}
