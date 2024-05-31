<?php

declare(strict_types=1);

namespace Danilocgsilva\ClassToSqlSchemaScript;

class TableScriptSpitter implements SpitterInterface
{
    private string $charset = "utf8mb3";

    private string $collate = "utf8mb3_unicode_ci";

    public array $fields = [];
    
    public function __construct(private readonly string $tableName)
    {
    }

    public function addField(FieldScriptSpitter $field)
    {
        $this->fields[] = $field;
    }

    public function getScript(): string
    {
        $baseString = "CREATE TABLE %s (\n";
        $baseString .= "    " . $this->fields[0]->getScript() . "\n";
        $baseString .= ") ENGINE=InnoDB DEFAULT CHARSET=%s COLLATE=%s;";
        return sprintf($baseString, $this->tableName, $this->charset, $this->collate);
    }
}
