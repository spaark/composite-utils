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
use \Throwable;

/**
 * Thrown when a property cannot be accessed for any reason
 */
class PropertyAccessException extends Exception
{
    /**
     * The type of access for the error message
     *
     * Overridden by child classes to provide detailed error feedback.
     * Example values might be 'read' or 'write'.
     */
    const ACCESS_TYPE = 'access';

    /**
     * The type of error for the error message
     *
     * Overridden by child classes to provide detailed error feedback.
     * Example values might be 'Permission denied' or 'Property does not
     * exist'
     */
    const ERROR_REASON = 'Generic Failure';

    /**
     * Creates the exception, populating its error message from class
     * and property names
     *
     * @param string $class The classname accessed
     * @param string $property The property accessed
     * @param Throwable $previous The exception which caused this
     */
    public function __construct
    (
        string $class,
        string $property,
        Throwable $previous = null
    )
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
