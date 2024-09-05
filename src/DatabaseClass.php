<?php

declare(strict_types=1);

namespace Danilocgsilva\ClassToSqlSchemaScript;

class DatabaseClass
{
    /* @var \Danilocgsilva\ClassToSqlSchemScript\TableClass[] */
    public readonly array $tables;
    
    public function __construct(private readonly string $databaseName)
    {
    }   
}
