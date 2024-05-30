<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;

class TableScriptSpitterTest extends TestCase
{
    public function testScriptMedicinesSimpleTable(): void
    {
        $expectedString = <<<EOF
CREATE TABLE medicines ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb4_unicode_ci;
EOF;
        $tableScriptSpitter = new TableScriptSpitter("medicines");

        $this->assertSame($expectedString, $tableScriptSpitter->getScript());
    }

    public function testScriptOwnersSimpleTable(): void
    {
        $expectedString = <<<EOF
CREATE TABLE owners ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb4_unicode_ci;
EOF;
        $tableScriptSpitter = new TableScriptSpitter("owners");

        $this->assertSame($expectedString, $tableScriptSpitter->getScript());
    }
}
