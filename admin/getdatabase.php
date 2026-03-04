<?php

include 'mysqldump.php';
$date = date('Y-m-d') . time();
$dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host=localhost;dbname=votes', 'root', '');
$dump->start('db/votes' . $date . '.sql');
header('Content-type: sql');
header('Content-Disposition: attachment; filename="votes' . $date . '.sql"');
readfile('db/votes' . $date . '.sql');
