<?php

declare(strict_types=1);

namespace Danilocgsilva\ClassToSqlSchemaScript;

use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;

class TableScriptSpitter implements SpitterInterface
{
    private string $charset = "utf8mb3";

    private string $collateSuffix = "unicode_ci";

    private FieldScriptSpitter|null $primaryKeyField = null;

    private $ifNotExists = false;

    private bool $escape = false;

    public array $fields = [];

    public function __construct(private readonly string $tableName)
    {
    }

    public function setEscape(): self
    {
        $this->escape = true;
        return $this;
    }

    public function addField(FieldScriptSpitter $field): self
    {
        $this->fields[] = $field;
        return $this;
    }

    public function createIfNotExists(): self
    {
        $this->ifNotExists = true;
        return $this;
    }

    public function setCollateSuffix(string $collateSuffix): self
    {
        $this->collateSuffix = $collateSuffix;
        return $this;
    }

    public function setPrimaryKey(string $fieldName): self
    {
        $primaryKeyField = $this->getFieldKeyByName($fieldName);
        $this->primaryKeyField = $this->fields[$primaryKeyField];
        return $this;
    }
    
    public function setCharSet(string $charset): self
    {
        $this->charset = $charset;
        return $this;
    }

    public function getScript(): string
    {
        $replacement = "%s";
        if ($this->escape) {
            $replacement = "`" . $replacement . "`";
        }
        $baseString = "CREATE TABLE {$replacement} (\n";
        if ($this->ifNotExists) {
            $baseString = sprintf($baseString, "IF NOT EXISTS {$replacement}");
        }

        foreach ($this->fields as $key => $field) {
            $baseString .= "    " . $field->getScript();
            end($this->fields);
            $lastFieldKey = $key !== key($this->fields);
            if ($lastFieldKey && $this->primaryKeyField === null || $this->primaryKeyField) {
                $baseString .= ",";
            }
            $baseString .= "\n";
        }

        if ($this->primaryKeyField) {
            $baseString .= sprintf("    PRIMARY KEY (%s)\n", $this->primaryKeyField->getName());
        }

        $baseString .= ") ENGINE=InnoDB DEFAULT CHARSET=%s COLLATE=%s;";
        return sprintf($baseString, $this->tableName, $this->charset, $this->charset . "_" . $this->collateSuffix);
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
