<?php

declare(strict_types=1);

namespace Danilocgsilva\ClassToSqlSchemaScript;

class DatabaseScriptSpitter implements SpitterInterface
{
    private string $charset = "utf8";

    private string $collate = "utf8mb4_unicode_ci";

    public function __construct(private readonly string $name)
    {
    }

    public function getScript(): string
    {
        $baseQuery = "CREATE DATABASE %s DEFAULT CHARACTER SET %s COLLATE %s;";
        return sprintf($baseQuery, $this->name, $this->charset, $this->collate);
    }
}
