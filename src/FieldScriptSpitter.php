<?php

declare(strict_types=1);

namespace Danilocgsilva\ClassToSqlSchemaScript;

use Danilocgsilva\ClassToSqlSchemaScript\TypeException;

class FieldScriptSpitter implements SpitterInterface
{
    private readonly string $type;

    private bool $unsigned = false;

    private bool $notNull = false;

    private bool $primaryKey = false;

    private bool $autoIncrement = false;

    public function __construct(private readonly string $name)
    {        
    }
    
    public function setType(string $type): self
    {
        if ($this->typeNotValid($type)) {
            throw new TypeException();
        }
        $this->type = $type;
        return $this;
    }

    public function setUnsigned(): self
    {   
        $this->unsigned = true;
        return $this;
    }

    public function setPrimaryKey(): self
    {
        $this->primaryKey = true;
        return $this;
    }

    public function setAutoIncrement(): self
    {
        $this->autoIncrement = true;
        return $this;
    }

    public function getScript(): string
    {
        $string = $this->name . " " . $this->type;
        if ($this->unsigned) {
            $string .= " UNSIGNED";
        }
        if ($this->notNull) {
            $string .= " NOT NULL";
        }
        if ($this->autoIncrement) {
            $string .= " AUTO_INCREMENT";
        }
        if ($this->primaryKey) {
            $string .= " PRIMARY KEY";
        }
        return $string;
    }

    public function setNotNull(): self
    {
        $this->notNull = true;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function typeNotValid(string $type): bool
    {
        if (preg_match("/VARCHAR/", $type)) {
            if (preg_match("/VARCHAR\(\d{1,3}\)/", $type)) {
                return false;
            }
            return true;
        }
        return false;
    }
}
