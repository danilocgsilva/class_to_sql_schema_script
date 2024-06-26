<?php

declare(strict_types=1);

namespace Tests;

use Danilocgsilva\ClassToSqlSchemaScript\ForeignKeyScriptSpitter;
use PHPUnit\Framework\TestCase;

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
CREATE DATABASE IF NOT EXISTS fieldsman_test;
USE fieldsman_test;

CREATE TABLE IF NOT EXISTS payloads (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    content TEXT
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS field_payload (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    field_id INT UNSIGNED NOT NULL,
    payload_id INT UNSIGNED NOT NULL
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE field_payload ADD CONSTRAINT \`field_payload_payload_constaint\` FOREIGN KEY (\`payload_id\`) REFERENCES payloads (\`id\`);  
EOF;

        $databaseScriptSpitter = new DatabaseScriptSpitter("fieldsman_test");
        $databaseScriptSpitter->setUseSelf();

        $tablePayloadsSpitter = new TableScriptSpitterPayload();
    }
}

