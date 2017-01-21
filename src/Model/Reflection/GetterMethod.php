<?php namespace Spaark\Core\Model\Reflection;

use Spaark\Core\Model\CannotCreateModelException;

class GetterMethod extends \Spaark\Core\Model\Base\Entity
{
    private $reflect;

    private $method;

    public $save;

    public function __fromCallback($cb)
    {
        $this->reflect = $cb[0];
        $this->method  = $cb[1];

        preg_match
        (
            '/@getter( |\t)*(.*?)( |\t)*(.*?)( |\t)*$/m',
            $this->method->getDocComment(),
            $getter
        );

        if (!$getter && !$this->method->isPublic())
        {
            throw new CannotCreateModelException
            (
                $this, 'callback', implode($cb)
            );
        }

        $this->save =
            isset($getter[2]) ? (trim($getter[2]) == 'save') : false;
    }
}