<?php

declare(strict_types=1);

namespace Danilocgsilva\ClassToSqlSchemaScript;

class FieldScriptSpitter implements SpitterInterface
{
    private readonly string $type;

    public function __construct(private readonly string $name)
    {        
    }
    
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getScript(): string
    {
        return $this->name . " " . $this->type;
    }
}
