<?php
function swap_for_faker_method($dataType)
{
    $dataType = strtoupper(trim($dataType));
    preg_match("/([A-Z]+)(\((.*?)\))?/", $dataType, $matches);
    $baseType = $matches[1];
    $sizeOrValues = isset($matches[3]) ? $matches[3] : null;

    switch($baseType){
        //numeric types
        case "TINYINT":
            return ($sizeOrValues === 1) ? "boolean" : "numberBetween(0, 127)";
        case "SMALLINT":
            return "numberBetween(0, 32767)";
        case "MEDIUMINT":
            return "numberBetween(0, 8388607)";
        case "INT":
        case "INTEGER":
            return "numberBetween(0, 2147483647)";
        case "BIGINT":
            return "numberBetween(0, PHP_INT_MAX)";
        case "DECIMAL":
        case "NUMERIC":
        case "FLOAT":
        case "DOUBLE":
        case "REAL":
            return "randomFloat(" . ($sizeOrValues ?? 2) . ", 0, 1000)";

        //string types
        case "CHAR":
        case "VARCHAR":
            return "lexify('?" . str_repeat("?", ($sizeOrValues ?? 10) - 1) . "')";
        case "TEXT":
        case "TINYTEXT":
        case "MEDIUMTEXT":
        case "LONGTEXT":
            return "text(200)";
        case "ENUM":
            preg_match_all("/'([^']+)'/", $sizeOrValues, $enumMatches);
            $enumValues = isset($enumMatches[1]) ? $enumMatches[1] : [];
            return "randomElement(" . json_encode($enumValues) . ")";
        case "SET":
            preg_match_all("/'([^']+)'/", $sizeOrValues, $setMatches);
            $setValues = isset($setMatches[1]) ? $setMatches[1] : [];
            return "randomElement(" . json_encode($setValues) . ")";

        //date & time types
        case "DATE":
            return "date('Y-m-d')";
        case "DATETIME":
            return "dateTime->format('Y-m-d H:i:s')";
        case "TIMESTAMP":
            return "unixTime";
        case "TIME":
            return "time('H:i:s')";
        case "YEAR":
            return "year";

        //binary types
        case "BLOB":
        case "TINYBLOB":
        case "MEDIUMBLOB":
        case "LONGBLOB":
            return "base64_encode(text(50))";

        //other
        case "BOOLEAN":
            return "boolean";
        case "JSON":
            return "json_encode('key' => word, 'value' => randomNumber()])";
            
        default:
            return "UNKNOWN_TYPE";
    }
}