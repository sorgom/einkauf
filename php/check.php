<?php
$usr = $_POST['usr'];
$id = $_POST['id'];
$ck = $_POST['ck'];
$log = "data/$usr.json";

require_once('fio.php');

$checks = getchecks();
if ($ck == '1') $checks[$id] = true;
else unset($checks[$id]);
wchecks($checks);

?>