<?php
require_once("./utilities.php");
$username = trim(readline("Please enter MySQL username: "));
$password = trim(readline("Please enter MySQL password: "));

$connect = new mysqli("bravo.dasa.r.mil.uk", $username, $password);

$databases = [];

if ($connect->error)
{
    die($connect->error);
} else {

    $dbresult = $connect->query("SHOW DATABASES");

    while ($row = $dbresult->fetch_array())
    {
        $databases[] = $row[0];
    }

}

print_r($databases);

$dbIndex = trim(readline("Please select a Database by index number: "));
$database = $databases[$dbIndex];

$connect->select_db($database);

if ($connect->error)
{
    die($connect->error);
}

$csvOutput = "{$database}.csv";
if (!$file = fopen($csvOutput, "w"))
{
    echo "Could not open csv to write";
    exit();
} else {
    fputcsv($file, ["table", "columns", "rows"]);
}

$tableResult = $connect->query("SHOW TABLES");

while ($row = $tableResult->fetch_array())
{
    $tableName = $row[0];

    $columnDataResult = $connect->query("SHOW COLUMNS FROM `$tableName`");
    $columns = [];

    while ($columnRow = $columnDataResult->fetch_assoc())
    {
        $method = swap_for_faker_method($columnRow["Type"]);
        $columns[] = "{$columnRow["Field"]}:{$method}";
    }

    $rowsResult = $connect->query("SELECT COUNT(*) AS count FROM `$tableName`");
    $rowCount = $rowsResult->fetch_assoc()["count"];

    fputcsv($file, [
        $tableName,
        implode(", ", $columns),
        $rowCount
    ]);
}


fclose($file);
$connect->close();