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
use Spaark\CompositeUtils\Model\Reflection\Type\GenericType;
use Spaark\CompositeUtils\Model\Generic\GenericContext;
use Spaark\CompositeUtils\Exception\MissingContextException;
use Spaark\CompositeUtils\Traits\AutoConstructTrait;

/**
 * Used to retrieve the classname for an AbstractType
 */
class GenericNameProvider
{
    use AutoConstructTrait;

    const BASE = 'Spaark\CompositeUtils\Generic\\';

    /**
     * @var GenericContext
     * @construct optional
     */
    protected $context;

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
            case GenericType::class:
                return $this->inferGenericName($reflect);
        }
    }

    /**
     * Infers the serialized name of the given GenericType
     *
     * @param GenericType $reflect
     * @return string
     * @throws Exception
     */
    protected function inferGenericName(GenericType $reflect)
    {
        if (!$this->context)
        {
            throw new MissingContextException();
        }

        return $this->inferName
        (
            $this->context->getGenericType($reflect->name)
        );
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
