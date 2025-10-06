<?php

include 'mysqldump.php';
$date = date('Y-m-d') . time();
$dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host=localhost;dbname=myvote', 'root', '');
$dump->start('db/myvote' . $date . '.sql');
header('Content-type: sql');
header('Content-Disposition: attachment; filename="myvote' . $date . '.sql"');
readfile('db/myvote' . $date . '.sql');
