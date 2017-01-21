<?php namespace Spaark\Core\Model\Reflection;
/**
 * Spaark
 *
 * Copyright (C) 2012 Emily Shepherd
 * emily@emilyshepherd.me
 */


use \Spaark\Core\Config\Config;


class UnresolveableWildcardsException extends \Exception
{
    public function __construct($method)
    {
        parent::__construct
        (
              'The param rules for ' . $method . ' contained one or '
            . 'more variable number of parameters - this makes '
            . 'resolving impossible'
        );
    }
}

class NoSuchClassException extends \Exception
{
    public function __construct($class)
    {
        parent::__construct($class . ' could not be resolved');
    }
}


class Method
{
    private $reflector;

    private $vars     = array( );

    private $requiredCount = 0;
    private $optionalCount = 0;

    public function __construct(\ReflectionMethod $reflector)
    {
        $this->reflector = $reflector;

        $this->parseDoc();
        $this->generateParameters();
    }

    public function isPublic()
    {
        return $this->reflector->isPublic();
    }

    public function invokeArgs($obj, $args)
    {
        return $this->reflector->invokeArgs($obj, $args);
    }

    protected function parseDoc()
    {
        //@param <ClassName | scalar>             $var
        //@param <ClassName | scalar>{number | *} $var
        //@param <ClassName | scalar>.from        $var
        //@param <ClassName | scalar>.from(var)   $var
        preg_match_all
        (
              '/'
            .     '@param '
            .     '([a-zA-Z0-9_\\\\]+)'
            .     '('
            .         '(\.([a-zA-Z0-9]+)(\(([a-zA-Z0-9_]+)\))?)' . '|'
            .         '\{([0-9]+|\*)\}'
            .     ')?'
            .     '[\t\s]+'
            .     '\$([a-zA-Z_][a-zA-Z0-9_]*)'
            . '/',
              $this->reflector->getDocComment(),
              $arr
        );

        $star = false;

        foreach ($arr[0] as $i => $val)
        {
            $param = new Param($arr[1][$i]);

            if ($arr[3][$i])
            {
                $param->from = $arr[4][$i];

                if ($arr[6][$i])
                {
                    $param->fromArg = $arr[4][$i];
                }
            }
            else if ($arr[7][$i])
            {
                $param->args = $arr[7][$i];
            }

            $var              = $arr[8][$i];
            $this->vars[$var] = $param;

            if ($param->args == '*')
            {
                if (!$star)
                {
                    $star = true;
                }
                else
                {
                    throw new UnresolveableWildcardsException
                    (
                        $this->reflector->getName()
                    );
                }
            }

            if
            (
                $param->cast{0} == '\\'                      ||
                in_array($param->cast, array('int', 'bool')) ||
                !$param->cast
            )
            {
                continue;
            }

            $localScope =
                  '\\'
                . $this->reflector->getDeclaringClass()->getNamespaceName()
                . '\\'
                . $param->cast;

            if (class_exists($localScope))
            {
                $this->vars[$var]->cast = $localScope;

                continue;
            }

            $appModelScope = Config::NAME_SPACE() . 'Model\\' . $param->cast;
            if (class_exists($appModelScope))
            {
                $this->vars[$var]->cast = $appModelScope;

                continue;
            }

            $spaarkModelScope = '\\Spaark\\Core\\Model\\' . $param->cast;
            if (class_exists($spaarkModelScope))
            {
                $this->vars[$var]->cast = $spaarkModelScope;

                continue;
            }

            throw new NoSuchClassException($param->cast);
        }
    }

    protected function generateParameters()
    {
        $params   = $this->reflector->getParameters();

        foreach ($params as $i => $param)
        {
            if (isset($this->vars[$param->getName()]))
            {
                $var = $this->vars[$param->getName()];
            }
            else
            {
                $cast = $param->getClass();
                $cast = $cast ? $cast->getName() : NULL;

                $var = new Param($cast);
            }

            if (!$param->isOptional())
            {
                $this->requiredCount += $var->args;
            }
            else
            {
                $var->optional = true;
                $var->default  = $param->getDefaultValue();

                $this->optionalCount += $var->args;
            }

            $this->vars[] = $var;
        }
    }

    public function getParameters()
    {
        return array_values($this->vars);
    }

    public function getMaxParamCount()
    {
        return $this->requiredCount + $this->optionalCount;
    }

    public function getMinParamCount()
    {
        return $this->requiredCount;
    }
}

