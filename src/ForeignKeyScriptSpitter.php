<?php

declare(strict_types=1);

namespace Danilocgsilva\ClassToSqlSchemaScript;

use Exception;

class ForeignKeyScriptSpitter implements SpitterInterface
{
    private ?string $constraintName = null;
    private ?string $table = null;
    private ?string $foreignKey = null;
    private ?string $foreignTable = null;
    private ?string $tableForeignKey = null;

    public function setConstraintName(string $constraintName): self
    {
        $this->constraintName = $constraintName;
        return $this;
    }

    public function setTable(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function setForeignKey(string $foreignKey): self
    {
        $this->foreignKey = $foreignKey;
        return $this;
    }

    public function setForeignTable(string $foreignTable): self
    {
        $this->foreignTable = $foreignTable;
        return $this;
    }

    public function setTableForeignkey(string $tableForeignKey): self
    {
        $this->tableForeignKey = $tableForeignKey;
        return $this;
    }

    public function getScript(): string
    {
        if ($this->constraintName === null) {
            throw new Exception("Constraint name is not set.");
        }
        if ($this->table === null) {
            throw new Exception("Table name is not set.");
        }
        if ($this->foreignKey === null) {
            throw new Exception("Foreign key is not set.");
        }
        if ($this->foreignTable === null) {
            throw new Exception("Foreign table is not set.");
        }
        if ($this->tableForeignKey === null) {
            throw new Exception("Table foreign key is not set.");
        }

        return sprintf(
            "ALTER TABLE %s ADD CONSTRAINT `%s` FOREIGN KEY (`%s`) REFERENCES %s (`%s`);",
            $this->table,
            $this->constraintName,
            $this->foreignKey,
            $this->foreignTable,
            $this->tableForeignKey
        );
    }
}
