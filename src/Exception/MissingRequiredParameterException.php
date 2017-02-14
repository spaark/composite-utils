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
use \Throwable;

/**
 * Thrown when a required arguement was not passed to a method
 */
class MissingRequiredParameterException extends Exception
{
    /**
     * Creates the exception, populating its error message from class
     * and property names
     *
     * @param string $class The classname of the method
     * @param string $parameter The name of the missing parameter
     * @param Throwable The exception which caused this
     */
    public function __construct
    (
        string $class,
        string $parameter,
        Throwable $previous = null
    )
    {
        parent::__construct
        (
              'Missing required parameter in constructor. '
            . $class . ' requires a value for ' . $parameter,
            0,
            $previous
        );
    }
}
