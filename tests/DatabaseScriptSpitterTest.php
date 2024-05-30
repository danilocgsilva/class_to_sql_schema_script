<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Danilocgsilva\ClassToSqlSchemaScript\DatabaseScriptSpitter;

class DatabaseScriptSpitterTest extends TestCase
{
    public function testCreateBasicScript(): void
    {
        $expectedScript = <<<EOF
CREATE DATABASE wordpress_database DEFAULT CHARACTER SET utf8 COLLATE utf8mb4_unicode_ci;
EOF;
        $databaseScriptSpitter = new DatabaseScriptSpitter("wordpress_database");

        $this->assertSame($expectedScript, $databaseScriptSpitter->getScript());
    }

    public function testCreateCareSystemBasicScript(): void
    {
        $expectedScript = <<<EOF
CREATE DATABASE care_system DEFAULT CHARACTER SET utf8 COLLATE utf8mb4_unicode_ci;
EOF;
        $databaseScriptSpitter = new DatabaseScriptSpitter("care_system");

        $this->assertSame($expectedScript, $databaseScriptSpitter->getScript());
    }
}
