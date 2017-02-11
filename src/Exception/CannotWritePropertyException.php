<?php

namespace Spaark\CompositeUtils\Exception;

class CannotWritePropertyException extends NonExistentPropertyException
{
    const ACCESS_TYPE = 'write';
}
