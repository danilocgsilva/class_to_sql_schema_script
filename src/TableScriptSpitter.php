<?php

declare(strict_types=1);

namespace Danilocgsilva\ClassToSqlSchemaScript;

class TableScriptSpitter implements SpitterInterface
{
    private string $charset = "utf8mb3";

    private string $collate = "utf8mb4_unicode_ci";

    public readonly array $fields;
    
    public function __construct(private readonly string $tableName)
    {
    }

    public function getScript(): string
    {
        $baseString = "CREATE TABLE %s ENGINE=InnoDB DEFAULT CHARSET=%s COLLATE=%s;";
        return sprintf($baseString, $this->tableName, $this->charset, $this->collate);
    }
}
