<?php
/**
 * This file is part of the Composite Utils package.
 *
 * (c) Emily Shepherd <emily@emilyshepherd.me>
 *
 * For the full copyright and licence information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package spaark/composite-utils
 * @author Emily Shepherd <emily@emilyshepherd>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Exception;

use \Exception;

class IllegalPropertyTypeException extends Exception
{
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
