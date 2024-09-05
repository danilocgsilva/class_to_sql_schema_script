<?php

declare(strict_types=1);

namespace Danilocgsilva\ClassToSqlSchemaScript;

use Exception;

class AlterTableScriptSpitter implements SpitterInterface
{
    private readonly string $newColumn;

    private bool $unsigned = false;

    private bool $notNull = false;

    private readonly string $type;
    
    public function __construct(private readonly string $tableName)
    {
    }
    
    public function getScript(): string
    {
        $baseQuery = "ALTER TABLE %s ADD COLUMN %s %s%s;";

        $extras = "";
        if ($this->unsigned) {
            $extras .= " UNSIGNED";
        }

        if ($this->notNull) {
            $extras .= " NOT NULL";
        }

        return sprintf($baseQuery, $this->tableName, $this->newColumn, $this->type, $extras);
    }

    public function setNewColumn(string $newColumn): self
    {
        $this->newColumn = $newColumn;
        return $this;
    }

    public function setUnsigned(): self
    {
        $this->unsigned = true;
        return $this;
    }

    public function setNotNull(): self
    {
        $this->notNull = true;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }
}
