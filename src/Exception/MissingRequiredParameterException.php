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

class MissingRequiredParameterException extends \Exception
{
    public function __construct($class, $property, $previous = null)
    {
        parent::__construct
        (
              'Missing required parameter in constructor. '
            . $class . ' requires a value for ' . $property,
            0,
            $previous
        );
    }
}
