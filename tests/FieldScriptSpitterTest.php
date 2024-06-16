<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\TypeException;
use PHPUnit\Framework\Attributes\DataProvider;

class FieldScriptSpitterTest extends TestCase
{
    public function testVarchar192GetString(): void
    {
        $expectedString = "name VARCHAR(192)";
        $fieldSpitter = new FieldScriptSpitter("name");
        $fieldSpitter->setType("VARCHAR(192)");
        $this->assertSame($expectedString, $fieldSpitter->getScript());
    }

    #[DataProvider('providesFieldName')]
    public function testIntGetString(string $fieldName): void
    {
        $expectedString = sprintf("%s INT", $fieldName);
        $fieldSpitter = new FieldScriptSpitter($fieldName);
        $fieldSpitter->setType("INT");
        $this->assertSame($expectedString, $fieldSpitter->getScript());
    }

    #[DataProvider('providesFieldName')]
    public function testGetName(string $fieldName): void
    {
        $field = new FieldScriptSpitter($fieldName);
        $this->assertSame($fieldName, $field->getName());
    }

    #[DataProvider('providesFieldName')]
    public function testUnsigned(string $fieldName): void
    {
        $field = (new FieldScriptSpitter($fieldName))
            ->setType("INT UNSIGNED");

        $expectedString = sprintf("%s INT UNSIGNED", $fieldName);

        $this->assertSame($expectedString, $field->getScript());
    }

    #[DataProvider('providesFieldName')]
    public function testUnsignedWithMethod(string $fieldName): void
    {
        $field = (new FieldScriptSpitter($fieldName))
            ->setUnsigned()
            ->setType("INT");

        $expectedString = sprintf("%s INT UNSIGNED", $fieldName);

        $this->assertSame($expectedString, $field->getScript());
    }

    #[DataProvider('providesFieldName')]
    public function testNotNull(string $fieldName): void
    {
        $field = (new FieldScriptSpitter($fieldName))
            ->setNotNull()
            ->setType("INT");

        $expectedString = sprintf("%s INT NOT NULL", $fieldName);

        $this->assertSame($expectedString, $field->getScript());
    }

    #[DataProvider('providesFieldName')]
    public function testUnsignedAndNotNull(string $fieldName): void
    {
        $field = (new FieldScriptSpitter($fieldName))
            ->setUnsigned()
            ->setNotNull()
            ->setType("INT");

        $expectedString = sprintf("%s INT UNSIGNED NOT NULL", $fieldName);
        $this->assertSame($expectedString, $field->getScript());
    }

    #[DataProvider('providesFieldName')]
    public function testNotNullAndUnsigned(string $fieldName): void
    {
        $field = (new FieldScriptSpitter($fieldName))
            ->setNotNull()
            ->setUnsigned()
            ->setType("INT");

        $expectedString = sprintf("%s INT UNSIGNED NOT NULL", $fieldName);
        $this->assertSame($expectedString, $field->getScript());
    }

    #[DataProvider('providesFieldName')]
    public function testPrimaryKey(string $fieldName): void
    {
        $field = (new FieldScriptSpitter($fieldName))
            ->setPrimaryKey()
            ->setType("INT");

        $expectedString = sprintf("%s INT PRIMARY KEY", $fieldName);
        $this->assertSame($expectedString, $field->getScript());
    }

    #[DataProvider('providesFieldName')]
    public function testTypeException(string $fieldName)
    {
        $this->expectException(TypeException::class);
        $fieldScriptSpitter = new FieldScriptSpitter($fieldName);
        $fieldScriptSpitter->setType("VARCHAR(255");
    }

    public static function providesFieldName(): array
    {
        return [
            ["id"],
            ["person_id"],
            ["drink_id"],
            ["doc_number"],
            ["person_name"]
        ];
    }
}
