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
}

