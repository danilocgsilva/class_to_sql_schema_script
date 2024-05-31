<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;

class FieldScriptSpitterTest extends TestCase
{
    public function testVarchar192GetString(): void
    {
        $expectedString = "name VARCHAR(192)";
        $fieldSpitter = new FieldScriptSpitter("name");
        $fieldSpitter->setType("VARCHAR(192)");
        $this->assertSame($expectedString, $fieldSpitter->getScript());
    }

    public function testIntGetString(): void
    {
        $expectedString = "doc_number INT";
        $fieldSpitter = new FieldScriptSpitter("doc_number");
        $fieldSpitter->setType("INT");
        $this->assertSame($expectedString, $fieldSpitter->getScript());
    }
}
