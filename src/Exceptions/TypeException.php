<?php

declare(strict_types=1);

namespace Danilocgsilva\ClassToSqlSchemaScript\Exceptions;

use Exception;

class TypeException extends Exception
{
    public function __construct()
    {
        parent::__construct("The given type is not allowed.");
    }
}
