<?php

declare(strict_types=1);

namespace Danilocgsilva\ClassToSqlSchemaScript;

interface SpitterInterface
{
    public function getScript(): string;
}
