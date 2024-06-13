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

    public function testFieldsEmptyFields(): void
    {
        $expectedString = <<<EOF
CREATE TABLE fields (
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
EOF;
        $tableScriptSpitter = new TableScriptSpitter("fields");

        $this->assertSame($expectedString, $tableScriptSpitter->getScript());
    }

    public function testIfNotExists(): void
    {
        $expectedString = <<<EOF
CREATE TABLE IF NOT EXISTS drinks (
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
EOF;

        $tableScriptSpitter = new TableScriptSpitter("drinks");
        $tableScriptSpitter->createIfNotExists();

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

    public function test2SeveralFields(): void
    {
        $exptectedString = <<<EOF
CREATE TABLE deliveries (
    id INT,
    name VARCHAR(192),
    item VARCHAR(192),
    amount INT,
    total INT,
    code VARCHAR(192)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
EOF;

        $table = new TableScriptSpitter("deliveries");

        $field1 = (new FieldScriptSpitter("id"))
            ->setType("INT");

        $field2 = (new FieldScriptSpitter("name"))
            ->setType("VARCHAR(192)");

        $field3 = (new FieldScriptSpitter("item"))
            ->setType("VARCHAR(192)");

        $field4 = (new FieldScriptSpitter("amount"))
            ->setType("INT");

        $field5 = (new FieldScriptSpitter("total"))
            ->setType("INT");

        $field6 = (new FieldScriptSpitter("code"))
            ->setType("VARCHAR(192)");

        $table->addField($field1)
            ->addField($field2)
            ->addField($field3)
            ->addField($field4)
            ->addField($field5)
            ->addField($field6);

        $this->assertSame($exptectedString, $table->getScript());
    }

    public function testFluentInterface(): void
    {
        $exptectedString = <<<EOF
CREATE TABLE projects (
    id INT,
    name VARCHAR(192),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
EOF;

        $table = new TableScriptSpitter("projects");

        $field1 = new FieldScriptSpitter("id");
        $field1->setType("INT");

        $field2 = new FieldScriptSpitter("name");
        $field2->setType("VARCHAR(192)");

        $table
            ->addField($field1)
            ->addField($field2)
            ->setPrimaryKey("id");

        $this->assertSame($exptectedString, $table->getScript());
    }

    #[DataProvider('providesTableNames')]
    public function testGetScript(string $tableName): void
    {
        $exptectedString = sprintf(<<<EOF
CREATE TABLE IF NOT EXISTS %s (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
EOF
        , $tableName);    

        $table = (new TableScriptSpitter($tableName))->createIfNotExists();
        $table->setCharSet("utf8mb4");
        $table->setCollateSuffix("general_ci");

        $integerField = (new FieldScriptSpitter("id"))
            ->setUnsigned()
            ->setType("INT")
            ->setAutoIncrement()
            ->setNotNull()
            ->setPrimaryKey();

        $nameField = (new FieldScriptSpitter("name"))
            ->setType("VARCHAR(255)");

        $table
            ->addField($integerField)
            ->addField($nameField);

        $this->assertSame($exptectedString, $table->getScript());
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

    public static function providesTableNames(): array
    {
        return [
            ["payloads"],
            ["fields"]
        ];
    }
}
