<?php
include_once __DIR__ . '/../../include/config.php';

$file_name = "excel_export_".date("Ymd_His");
$file_ending = "xls";

//header info for browser
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$file_name.xls");
header("Pragma: no-cache");
header("Expires: 0");
$pl = new \Congreso\Logica\Participante();
$resultado = $pl->listParticipantes();

/*******Start of Formatting for Excel*******/
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character
//--start of printing column names as names of MySQL fields

$rows = $resultado["rows"];

$columns = array_keys(end($rows));
reset($rows);
foreach($columns as $c) {
    echo $c."\t";
}
print("\n");
//end of printing column names
//start while loop to get data
foreach($rows as $row) {
    $schema_insert = '';

    foreach ($row as $col => $val) {
        if (is_null($val)) {
            $schema_insert .= "NULL" . $sep;
        } elseif ($val != '') {
            $schema_insert .= "$val" . $sep;
        } else {
            $schema_insert .= "" . $sep;
        }
    }
    $schema_insert = str_replace($sep . "$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t";
    print(trim($schema_insert));
    print "\n";
}
?>