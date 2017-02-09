<?php

namespace Spaark\Core\Exception;

class CannotWritePropertyException extends NonExistentPropertyException
{
    const ACCESS_TYPE = 'write';
}
