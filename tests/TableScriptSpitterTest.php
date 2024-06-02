<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
use PHPUnit\Framework\Attributes\DataProvider;

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

    #[DataProvider('providesAddField')]
    public function testScriptOwnersSimpleTable(string $tableName, string $field): void
    {
        $expectedStringBase = <<<EOF
CREATE TABLE %s (
    %s INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
EOF;
        $tableScriptSpitter = new TableScriptSpitter($tableName);
        $tableScriptSpitter->addField(
            (new FieldScriptSpitter($field))
                ->setType("INT")
        );

        $this->assertSame(
            sprintf($expectedStringBase, $tableName, $field),
            $tableScriptSpitter->getScript()
        );
    }

    #[DataProvider('providesFieldsAndTableNames')]
    public function testPrimaryKey(string $tableName, string $fieldName, string $type): void
    {
        $expectedStringBase = <<<EOF
CREATE TABLE %s (
    %s %s,
    PRIMARY KEY (%s)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
EOF;
        $tableScriptSpitter = new TableScriptSpitter($tableName);
        $idField = new FieldScriptSpitter($fieldName);
        $idField->setType($type);
        $tableScriptSpitter->addField($idField);
        $tableScriptSpitter->setPrimaryKey($fieldName);
        $this->assertSame(
            sprintf($expectedStringBase, $tableName, $fieldName, $type, $fieldName),
            $tableScriptSpitter->getScript()
        );
    }

    public function testSeveralFields(): void
    {
        $tableName = "address";
        
        $fields = [
            "    name VARCHAR(192),\n",
            "    phone VARCHAR(32)\n"
        ];

        $expectedString = sprintf("CREATE TABLE %s (\n", $tableName);
        foreach ($fields as $field) {
            $expectedString .= $field;
        }
        $expectedString .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;";

        $tableScriptSpitter = new TableScriptSpitter($tableName);

        $field1 = new FieldScriptSpitter("name");
        $field1->setType("VARCHAR(192)");

        $field2 = new FieldScriptSpitter("phone");
        $field2->setType("VARCHAR(32)");

        $tableScriptSpitter->addField($field1);
        $tableScriptSpitter->addField($field2);

        $this->assertSame(
            $expectedString,
            $tableScriptSpitter->getScript()
        );
    }

    public static function providesFieldsAndTableNames(): array
    {
        return [
            ["cars", "id", "INT"],
            ["client", "client_id", "INT"]
        ];
    }

    public static function providesAddField(): array
    {
        return [
            ["owners", "doc_number"],
            ["deliverer", "email"]
        ];
    }
}
