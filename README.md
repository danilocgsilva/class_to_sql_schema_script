# Class to sql schema script

1. Create a classe with the database properies.

2. Get the sql script.

Example:
```
use Danilocgsilva\ClassToSqlSchemaScript\DatabaseScriptSpitter;
.
.
.
$databaseScriptSpitter = new DatabaseScriptSpitter("your_database_name");

return $databaseScriptSpitter->getString();
```
It will result in:
```
CREATE DATABASE your_database_name DEFAULT CHARACTER SET utf8 COLLATE utf8mb3_unicode_ci;
```
Check more usage examples in tests.

## Todo: allow unique key

```
CREATE TABLE neighborhood (
    `id` INT UNSIGNED NOT NULL PRIMARY KEY,
    `name` VARCHAR(255),
    `parent_place` INT UNSIGNED UNIQUE,
    `location_type` VARCHAR(32)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
```
