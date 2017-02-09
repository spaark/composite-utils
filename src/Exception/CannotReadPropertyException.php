<?php

namespace Spaark\Core\Exception;

class CannotReadPropertyException extends NonExistentPropertyException
{
    const ACCESS_TYPE = 'read';
}
