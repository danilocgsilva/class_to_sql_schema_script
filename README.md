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
