<?php

$database = ['myvote'];
$user = "root";
$pass = "";
$host = "localhost";

date_default_timezone_get('Asia/Manila');

if (!file_exists("autobackupdb/")) {
    mkdir("autobackupdb/");
}
foreach ($database as $databases) {
    if (!file_exists(("autobackupdb/$databases"))) {
        $old = umask(0);
        mkdir("autobackupdb/$databases", 0777, true);
        umask($old);
        chmod($dir, 0777);
    }
    $filename = $databases . "_" . date("F_d_Y") . "@" . date("g_ia") . uniqid("_", false);
    $folder = "autobackupdb/" . $filename . ".sql";
    exec("C:/wamp64/bin/mysql/mysql5.7.36/bin/mysqldump --user={$user} --password={$pass} --host={$host} {$databases} --result-file={$folder}", $output);
}
print_r($output);
