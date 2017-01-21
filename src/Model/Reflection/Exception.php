<?php namespace Spaark\Core\Model\Reflection;
/**
 * Spaark
 *
 * Copyright (C) 2012 Emily Shepherd
 * emily@emilyshepherd.me
 */

class Exception extends Model implements \IteratorAggregate
{
    private $exception;

    private $stack;

    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    public function getIterator()
    {
        if (!$this->stack) $this->build();

        return new \ArrayIterator($this->stack);
    }

    public function type()
    {
        return get_class($this->exception);
    }

    public function line()
    {
        return $this->exception->getLine();
    }

    public function file()
    {
        return $this->exception->getFile();
    }

    public function message()
    {
        return $this->exception->getMessage();
    }

    public function build()
    {
        $skipping = false;
        $trace    = $this->exception->getTrace();
        $output   = array( );

        foreach ($trace as $i => &$step)
        {
            if (!isset($step['class']))
            {
                switch ($step['function'])
                {
                    case 'call_user_func':
                        $step = $this->call_user_func
                        (
                            $step,
                            $step['args'][0],
                            array_slice($step['args'], 1)
                        );
                        break;

                    case 'call_user_func_array':
                        $step = $this->call_user_func
                        (
                            $step,
                            $step['args'][0],
                            $step['args'][1]
                        );
                        break;
                }
            }

            if (isset($step['class']))
            {
                if (in_array($step['function'], array('__call', '__callStatic')))
                {
                    if (!isset($step['substack']))
                    {
                        $stack = array($step);
                    }
                    else
                    {
                        $stack = $step['substack'];
                        unset($step['substack']);
                        $stack[] = $step;
                    }

                    unset($trace[$i]);
                    $trace[$i + 1]['substack'] = $stack;
                    continue;
                }
                elseif
                (
                    isset($trace[$i + 1])                  &&
                    isset($trace[$i + 1]['class'])         &&
                    $trace[$i + 1]['function'] == '__call' &&
                    count($trace[$i + 1]['args'])          &&
                    '_' . $trace[$i + 1]['args'][0] == $step['function']
                )
                {
                    unset($trace[$i]);
                    $trace[$i + 1]['substack'] = array($step);
                }
            }

            if ($skipping)
            {
                if
                (
                    isset($trace[$i + 1])          &&
                    isset($trace[$i + 1]['class']) &&
                    strpos($trace[$i + 1]['class'], 'Spaark') !== 0
                )
                {
                    $skipping = false;
                }
                else
                {
                    continue;
                }
            }
            elseif
            (
                isset($step['class']) &&
                strpos($step['class'], 'Spaark') === 0
            )
            {
                $skipping = true;

                if ($i == 0)
                {
                    continue;
                }
            }

            $output[] = $trace[$i];
        }

        $this->stack = $trace;
    }

    private function call_user_func($step, $cb, $args)
    {
        $newStep = array
        (
            'file' => $step['file'],
            'line' => $step['line'],
            'args' => $args
        );

        if (is_string($cb))
        {
            $parts = explode('::', $cb);
            if (count($parts) == 2)
            {
                $newStep['class']    = $parts[0];
                $newStep['function'] = $parts[1];
                $newStep['type']     = '::';
            }
            else
            {
                $newStep['function'] = $cb;
            }
        }
        elseif (is_array($cb) && count($cb) == 2)
        {
            if (is_object($cb[0]))
            {
                $newStep['class']    = get_class($cb[0]);
                $newStep['function'] = $cb[1];
                $newStep['type']     = '->';
            }
            else
            {
                $newStep['class']    = $cb[0];
                $newStep['function'] = $cb[1];
                $newStep['type']     = '::';
            }
        }
        elseif (is_object($cb) && is_a($cb, 'Closure'))
        {
            $newStep['function'] = '{closure}';
        }
        else
        {
            return $step;
        }

        return $newStep;
    }
}