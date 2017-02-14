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

namespace Spaark\CompositeUtils\Exception;

use \Exception;

/**
 * Throw when attempting to set the value of a property using an
 * unacceptable property type
 */
class IllegalPropertyTypeException extends Exception
{
    /**
     * Creates the exception, populating its error message from class
     * and property names, the expected type and the type that was given
     *
     * @param string $class The classname accessed
     * @param string $property The property accessed
     * @param string $expected The expected type
     * @param string $got The type that was provided
     * @param Exception $previous The exception which caused this
     */
    public function __construct
    (
        string $class,
        string $property,
        string $expected,
        string $got,
        Exception $previous = null
    )
    {
        parent::__construct
        (
              'Tried to set an illegal property type for '
            . $class .'::$' . $property . '. Excpected ' . $expected
            . ', got ' . $got,
            0,
            $previous
        );
    }
}
