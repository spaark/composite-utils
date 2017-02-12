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

namespace Spaark\CompositeUtils\Factory\Reflection;

use Spaark\CompositeUtils\Factory\BaseFactory;
use Spaark\CompositeUtils\Model\Reflection\ReflectionFile;
use Spaark\CompositeUtils\Model\Reflection\NamespaceBlock;
use Spaark\CompositeUtils\Model\Reflection\UseStatement;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;
use \ReflectionClass as PHPNativeReflectionClass;

class ReflectionFileFactory extends ReflectorFactory
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @var int
     */
    protected $i = 0;

    /**
     * @var array
     */
    protected $tokens;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->object = new ReflectionFile();
        $this->accessor = new RawPropertyAccessor($this->object);
    }

    public function build()
    {
        $this->parseFile();

        return $this->object;
    }

    public function parseFile()
    {
        $tokens = token_get_all(file_get_contents($this->filename));

        $matching = null;
        $classname = '';
        $as = '';
        $currentNS = null;

        foreach ($tokens as $token)
        {
            if ($token === ';')
            {
                switch ($matching)
                {
                    case T_NAMESPACE:
                        $ns = new NamespaceBlock($classname);
                        $currentNS = new RawPropertyAccessor($ns);
                        $this->accessor->getRawValue
                        (
                            'namespaces'
                        )
                        ->add($classname, $ns);
                        $currentNS->setRawValue('file', $this->object);
                        $matching = null;
                        break;
                    case T_AS:
                    case T_USE:
                        if (!$as)
                        {
                            $as = explode('\\', $classname);
                            $as = end($as);
                        }

                        $currentNS->getRawValue
                        (
                            'useStatements'
                        )
                        ->add($as, new UseStatement($classname, $as));
                        $matching = null;
                        break;
                }
                continue;
            }

            if ($matching === T_AS)
            {
                if ($token[0] === T_STRING)
                {
                    $as .= $token[1];
                }
            }
            elseif ($matching)
            {
                switch ($token[0])
                {
                    case T_STRING:
                    case T_NS_SEPARATOR:
                        $classname .= $token[1];
                        break;
                    case T_AS:
                        $matching = T_AS;
                }
            }
            else
            {
                switch ($token[0])
                {
                    case T_NAMESPACE:
                    case T_USE:
                        $as = '';
                        $classname = '';
                        $matching = $token[0];
                }
            }
        }
    }
}
