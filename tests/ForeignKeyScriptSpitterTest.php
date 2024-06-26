<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Danilocgsilva\ClassToSqlSchemaScript\{
    DatabaseScriptSpitter, 
    TableScriptSpitter, 
    FieldScriptSpitter, 
    ForeignKeyScriptSpitter
};

class ForeignKeyScriptSpitterTest extends TestCase
{
    public function testGetScript(): void
    {
        $foreignKeyScriptSpitter = new ForeignKeyScriptSpitter();

        $expectedString = "ALTER TABLE field_payload ADD CONSTRAINT `field_payload_field_constraint` FOREIGN KEY (`field_id`) REFERENCES fields (`id`);";

        $foreignKeyScriptSpitter = new ForeignKeyScriptSpitter();
        $foreignKeyScriptSpitter->setConstraintName("field_payload_field_constraint");
        $foreignKeyScriptSpitter->setTable("field_payload");
        $foreignKeyScriptSpitter->setForeignKey("field_id");
        $foreignKeyScriptSpitter->setForeignTable("fields");
        $foreignKeyScriptSpitter->setTableForeignkey("id");

        $this->assertSame($expectedString, $foreignKeyScriptSpitter->getScript());
    }

    public function testScriptWithDatabaseScript(): void
    {
        $expectedString = <<<EOF
CREATE DATABASE fieldsman_test DEFAULT CHARACTER SET utf8 COLLATE utf8mb3_unicode_ci;
USE fieldsman_test;
CREATE TABLE payloads (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE field_payload (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    payload_id INT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
ALTER TABLE field_payload ADD CONSTRAINT `field_payload_payload_constaint` FOREIGN KEY (`payload_id`) REFERENCES payloads (`id`);
EOF;

        $databaseScriptSpitter = (new DatabaseScriptSpitter("fieldsman_test"))
            ->setUseSelf();

        $tablePayloadsSpitter = (new TableScriptSpitter("payloads"))
            ->addField(
                (new FieldScriptSpitter("id"))
                    ->setType("INT")
                    ->setUnsigned()
                    ->setNotNull()
                    ->setAutoIncrement()
                    ->setPrimaryKey()
            )
            ->addField(
                (new FieldScriptSpitter("name"))
                    ->setType("VARCHAR(255)")
            )
            ->setCharSet("utf8mb4")
            ->setCollateSuffix("general_ci");

        $tableFieldPayloadsSpitter = (new TableScriptSpitter("field_payload"))
            ->addField(
                (new FieldScriptSpitter("id"))
                    ->setPrimaryKey()
                    ->setType("INT")
                    ->setUnsigned()
                    ->setNotNull()
                    ->setAutoIncrement()
            )
            ->addField(
                (new FieldScriptSpitter("payload_id"))
                    ->setType("INT")
                    ->setUnsigned()
                    ->setNotNull()
            )            
            ->setCharSet("utf8mb4")
            ->setCollateSuffix("general_ci");

        $foreignKeyScriptSpitter = (new ForeignKeyScriptSpitter())
            ->setConstraintName("field_payload_payload_constaint")
            ->setTable("field_payload")
            ->setForeignKey("payload_id")
            ->setForeignTable("payloads")
            ->setTableForeignkey("id");

        $databaseScriptSpitter->addTableScriptSpitter($tablePayloadsSpitter)
            ->addTableScriptSpitter($tableFieldPayloadsSpitter)
            ->addTableScriptSpitter($foreignKeyScriptSpitter);

        $this->assertSame($expectedString, $databaseScriptSpitter->getScript());
    }
}

