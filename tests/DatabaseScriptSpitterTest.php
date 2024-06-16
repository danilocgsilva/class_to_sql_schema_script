<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Danilocgsilva\ClassToSqlSchemaScript\DatabaseScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
use PHPUnit\Framework\Attributes\DataProvider;

class DatabaseScriptSpitterTest extends TestCase
{
    public function testCreateBasicScript(): void
    {
        $expectedScript = <<<EOF
CREATE DATABASE wordpress_database DEFAULT CHARACTER SET utf8 COLLATE utf8mb3_unicode_ci;
EOF;
        $databaseScriptSpitter = new DatabaseScriptSpitter("wordpress_database");

        $this->assertSame($expectedScript, $databaseScriptSpitter->getScript());
    }

    public function testCreateCareSystemBasicScript(): void
    {
        $expectedScript = <<<EOF
CREATE DATABASE care_system DEFAULT CHARACTER SET utf8 COLLATE utf8mb3_unicode_ci;
EOF;
        $databaseScriptSpitter = new DatabaseScriptSpitter("care_system");

        $this->assertSame($expectedScript, $databaseScriptSpitter->getScript());
    }

    #[DataProvider('providesCollate')]
    public function testSetCollate(string $collate): void
    {
        $expectedScript = <<<EOF
CREATE DATABASE care_system DEFAULT CHARACTER SET utf8 COLLATE %s;
EOF;
        $databaseScriptSpitter = new DatabaseScriptSpitter("care_system");
        $databaseScriptSpitter->setCollate($collate);

        $this->assertSame(sprintf($expectedScript, $collate), $databaseScriptSpitter->getScript());
    }

    #[DataProvider('providesTableName')]
    public function testUseSelf(string $tableName): void
    {
        $expectedString = <<<EOF
CREATE DATABASE %s DEFAULT CHARACTER SET utf8 COLLATE utf8mb3_unicode_ci;
USE %s;
EOF;

        $databaseScriptSpitter = new DatabaseScriptSpitter($tableName);
        $databaseScriptSpitter->setUseSelf();

        $this->assertSame(sprintf($expectedString, $tableName, $tableName), $databaseScriptSpitter->getScript());
    }

    #[DataProvider('providesTableName')]
    public function testGetScriptWithSubTable(string $databaseName): void
    {
        $expectedString = <<<EOF
CREATE DATABASE %s DEFAULT CHARACTER SET utf8 COLLATE utf8mb4_general_ci;
CREATE TABLE payloads (
    name VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
EOF;
        $databaseScriptSpitter = new DatabaseScriptSpitter($databaseName);
        $databaseScriptSpitter->setCollate("utf8mb4_general_ci");

        $tableScriptSpitter = (new TableScriptSpitter("payloads"))
            ->addField(
                (new FieldScriptSpitter("name"))
                    ->setType("VARCHAR(255)")
            )
            ->setCollateSuffix("general_ci")
            ->setCharSet("utf8mb4");

        $databaseScriptSpitter->addTableScriptSpitter($tableScriptSpitter);

        $this->assertSame(sprintf($expectedString, $databaseName), $databaseScriptSpitter->getScript());
    }

    public static function providesTableName(): array
    {
        return [["sellers"],["drivers"],["customers"]];
    }

    public static function providesCollate(): array
    {
        return [["utf8mb4_general_ci"],["utf8mb3_unicode_ci"]];
    }
}
