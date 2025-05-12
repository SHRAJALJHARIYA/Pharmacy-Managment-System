<?php
include 'database/connect.php';

function backDb($host, $user, $pass, $dbname, $tables = '*') {
    // Make database connection
    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get all of the tables
    if($tables == '*') {
        $tables = array();
        $sql = "SHOW TABLES";
        $query = $conn->query($sql);
        while($row = $query->fetch_row()) {
            $tables[] = $row[0];
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

    // Generate SQL script
    $outsql = '';
    foreach ($tables as $table) {
        // Prepare SQL script for creating table structure
        $sql = "SHOW CREATE TABLE $table";
        $query = $conn->query($sql);
        $row = $query->fetch_row();
        $outsql .= "\n\n" . $row[1] . ";\n\n";

        $sql = "SELECT * FROM $table";
        $query = $conn->query($sql);

        $columnCount = $query->field_count;

        // Prepare SQL script for dumping data for each table
        for ($i = 0; $i < $columnCount; $i++) {
            while ($row = $query->fetch_row()) {
                $outsql .= "INSERT INTO $table VALUES(";
                for ($j = 0; $j < $columnCount; $j++) {
                    $row[$j] = $row[$j] ? "'" . $conn->real_escape_string($row[$j]) . "'" : "NULL";
                    $outsql .= $row[$j] . ($j < ($columnCount - 1) ? ',' : '');
                }
                $outsql .= ");\n";
            }
        }
        $outsql .= "\n";
    }

    // Save the SQL script to a backup file
    $backup_file_name = $dbname . '_backup_' . date('Y-m-d_H-i-s') . '.sql';
    $fileHandler = fopen($backup_file_name, 'w+');
    fwrite($fileHandler, $outsql);
    fclose($fileHandler);

    // Download the SQL backup file to the browser
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($backup_file_name));
    ob_clean();
    flush();
    readfile($backup_file_name);

    // Delete the backup file from the server
    unlink($backup_file_name);
}
?>