<?php

declare(strict_types=1);

namespace Danilocgsilva\ClassToSqlSchemaScript;

use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;

class TableScriptSpitter implements SpitterInterface
{
    private string $charset = "utf8mb3";

    private string $collate = "utf8mb3_unicode_ci";

    private FieldScriptSpitter|null $primaryKeyField = null;

    public array $fields = [];
    
    public function __construct(private readonly string $tableName)
    {
    }

    public function addField(FieldScriptSpitter $field)
    {
        $this->fields[] = $field;
    }

    public function setPrimaryKey(string $fieldName): self
    {
        $primaryKeyField = $this->getFieldKeyByName($fieldName);
        $this->primaryKeyField = $this->fields[$primaryKeyField];
        return $this;
    }

    public function getScript(): string
    {
        $baseString = "CREATE TABLE %s (\n";

        foreach ($this->fields as $key => $field) {
            $baseString .= "    " . $field->getScript();
            end($this->fields);
            if ($key !== key($this->fields) && !$this->primaryKeyField) {
                $baseString .= ",";
            }
            $baseString .= "\n";
        }
        
        
        if ($this->primaryKeyField) {
            $baseString = rtrim($baseString, "\n");
            $baseStringPrimaryKey = ",\n    PRIMARY KEY (%s)\n";
            $baseString .= sprintf($baseStringPrimaryKey, $this->primaryKeyField->getName());
        }
        

        $baseString .= ") ENGINE=InnoDB DEFAULT CHARSET=%s COLLATE=%s;";
        return sprintf($baseString, $this->tableName, $this->charset, $this->collate);
    }

    public function getFieldKeyByName(string $fieldName): int
    {
        foreach ($this->fields as $key => $fieldLoop) {
            if ($fieldLoop->getName() === $fieldName) {
                return $key;
            }
        }
        throw new \Exception("There's no a field with name " . $fieldName . ".");
    }
}
