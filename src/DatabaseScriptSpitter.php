<?php

declare(strict_types=1);

namespace Danilocgsilva\ClassToSqlSchemaScript;

class DatabaseScriptSpitter implements SpitterInterface
{
    private string $charset = "utf8";

    private string $collate = "utf8mb3_unicode_ci";

    private bool $ifNotExists = false;

    private bool $useSelf = false;

    public function __construct(private readonly string $name)
    {
    }

    public function getScript(): string
    {
        $baseQuery = "CREATE DATABASE %s DEFAULT CHARACTER SET %s COLLATE %s;";
        $creatingName = $this->name;
        if ($this->ifNotExists) {
            $creatingName = "IF NOT EXISTS " . $creatingName;
        }
        $fullScript = sprintf($baseQuery, $creatingName, $this->charset, $this->collate);
        if ($this->useSelf) {
            $fullScript .= sprintf("\nUSE %s;", $this->name);
        }
        return $fullScript;
    }

    public function setIfNotExists(): self
    {
        $this->ifNotExists = true;
        return $this;
    }

    public function setUseSelf(): self
    {
        $this->useSelf = true;
        return $this;
    }
}
