<?php

declare(strict_types=1);

namespace Tests;

use Danilocgsilva\ClassToSqlSchemaScript\AlterTableScriptSpitter;
use PHPUnit\Framework\TestCase;

class AlterTableScriptSpitterTest extends TestCase
{
    public function testGetAlter(): void
    {
        $expectedString = "ALTER TABLE dns ADD COLUMN platform_id INT UNSIGNED NOT NULL;";
        $alterTableScriptSpitter = new AlterTableScriptSpitter("dns");
        $alterTableScriptSpitter->setNewColumn("platform_id");
        $alterTableScriptSpitter->setUnsigned();
        $alterTableScriptSpitter->setNotNull();
        $alterTableScriptSpitter->setType("INT");

        $this->assertSame($expectedString, $alterTableScriptSpitter->getScript());
    }
}
