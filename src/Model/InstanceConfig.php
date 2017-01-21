<?php
/**
 *
 */

namespace Spaark\Core\Model;

use Spaark\Core\Instance;

class InstanceConfig extends Config
{
    /**
     * The global application config
     *
     * @readable
     * @var Config
     */
    protected $app;

    public static function _fromSingle()
    {
        $ret = new static();
        $ret->loadConfig(get_called_class());

        return $ret;
    }

    private function loadConfig($name)
    {
        $this->app = Instance::getconfig();
        $json      = new JSON();
        $name      = explode('\\', $name);
        $name      = strtolower($name[count($name) - 2]);
        $path      = ROOT . '/' .  $this->app->configPath . $name;
        $arr       = \tree_merge_recursive
        (
            $json->parseFile($path, false),
            (array)$this->app->propertyValue($name)
        );

        $this->loadArray($arr);
    }
}

