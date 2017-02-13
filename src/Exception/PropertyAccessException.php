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

namespace Spaark\CompositeUtils\Exception;

use \Exception;

class PropertyAccessException extends Exception
{
    const ACCESS_TYPE = 'access';
    const ERROR_REASON = 'Generic Failure';

    public function __construct($class, $property, $previous = null)
    {
        parent::__construct
        (
              'Cannot ' . static::ACCESS_TYPE . ' property: '
            . $class . '::$' . $property . '. ' . static::ERROR_REASON,
            0,
            $previous
        );
    }
}
