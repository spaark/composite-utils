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

use \LogicException;
use \Throwable;

/**
 * Thrown when a property cannot be accessed for any reason
 */
class MissingContextException extends LogicException
{
    /**
     * Creates the exception, populating its error message from class
     * and property names
     *
     * @param Throwable $previous The exception which caused this
     */
    public function __construct
    (
        Throwable $previous = null
    )
    {
        parent::__construct
        (
              'Cannot serialize object containing generics without '
            . 'context',
            0,
            $previous
        );
    }
}
