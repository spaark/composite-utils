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

use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Reflection\Type\AbstractType;
use Spaark\CompositeUtils\Model\Reflection\Type\BooleanType;
use Spaark\CompositeUtils\Model\Reflection\Type\FloatType;
use Spaark\CompositeUtils\Model\Reflection\Type\MixedType;
use Spaark\CompositeUtils\Model\Reflection\Type\IntegerType;
use Spaark\CompositeUtils\Model\Reflection\Type\CollectionType;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Exception\PropertyNotWritableException;
use Spaark\CompositeUtils\Exception\PropertyNotReadableException;

/**
 * Used to retrieve the classname for an AbstractType
 */
class GenericNameProvider
{
    const BASE = 'Spaark\CompositeUtils\Generic\\';

    /**
     * Infers the serialized name of the given AbstractType
     *
     * @param AbstractType $reflect
     * @return string
     */
    public function inferName(AbstractType $reflect)
    {
        switch (get_class($reflect))
        {
            case ObjectType::class:
                return $this->inferObjectName($reflect);
            case BooleanType::class:
                return 'boolean';
            case IntegerType::class:
                return 'int';
            case FloatType::class:
                return 'float';
            case MixedType::class:
                return '';
            case StringType::class:
                return 'string';
        }
    }

    /**
     * Infers the serialized name of the given ObjectType
     *
     * @param ObjectType $reflect
     * @return string
     */
    protected function inferObjectName(ObjectType $reflect)
    {
        if ($reflect->generics->empty())
        {
            return $reflect->classname;
        }
        else
        {
            $items = [];
            foreach ($reflect->generics as $generic)
            {
                $items[] = $this->inferName($generic);
            }

            return self::BASE . $reflect->classname
                . '_g' . implode('_c', $items) . '_e';
        }
    }
}
