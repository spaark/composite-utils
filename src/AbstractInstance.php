<?php
/**
 * Spaark
 *
 * Copyright (C) 2012 Emily Shepherd
 * emily@emilyshepherd.me
 */

namespace Spaark\Core;

use Spaark\Core\Model\InstanceConfig;

/**
 * 
 */
abstract class AbstractInstance implements SpaarkInstanceInterface
{
    private static $rootInstance;

    public static function bootstrap($app = 'app', $config = [])
    {
        $app = static::$rootInstance = new static($app);
    }

    private $appName;

    private $config;

    /**
     * @param string $app The application file
     */
    public function __construct($app)
    {
        $this->appName = $app;
    }
 }


