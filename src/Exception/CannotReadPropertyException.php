<?php

namespace Spaark\CompositeUtils\Exception;

class CannotReadPropertyException extends NonExistentPropertyException
{
    const ACCESS_TYPE = 'read';
}
