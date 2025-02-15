<?php
$user = "FF00E1A4";
$log = "data/$user.json";
$checked = array();

if (file_exists($log)) {
    $checked = json_decode(file_get_contents($log), true);
}

$id = $_POST['id'];
$ck = $_POST['ck'];

if ($ck == '1') {
    $checked[$id] = true;
} else {
    unset($checked[$id]);
}

file_put_contents($log, json_encode($checked));

?>