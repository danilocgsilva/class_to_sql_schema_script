<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;

class TableScriptSpitterTest extends TestCase
{
    public function testScriptMedicinesSimpleTable(): void
    {
        $expectedString = <<<EOF
CREATE TABLE medicines (
    name VARCHAR(192)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
EOF;
        $tableScriptSpitter = new TableScriptSpitter("medicines");
        $nameField = new FieldScriptSpitter("name");
        $nameField->setType("VARCHAR(192)");
        $tableScriptSpitter->addField($nameField);

        $this->assertSame($expectedString, $tableScriptSpitter->getScript());
    }

    public function testScriptOwnersSimpleTable(): void
    {
        $expectedString = <<<EOF
CREATE TABLE owners (
    doc_number INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
EOF;
        $tableScriptSpitter = new TableScriptSpitter("owners");
        $tableScriptSpitter->addField(
            (new FieldScriptSpitter("doc_number"))
                ->setType("INT")
        );

        $this->assertSame($expectedString, $tableScriptSpitter->getScript());
    }
}
