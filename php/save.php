<?php
require_once('usr.php');
checkusr();
if (isset($_POST['skip'])) go('/');

$data = trim($_POST['data']);
if (empty($data)) go('input.php');

if (file_exists($log) && file_exists($txt))
{
    require_once('fio.php');
    $lines = getlines();
    $checks = getchecks();
    $top = '';
    $map = array(); 
    $cnr = 0;
    $inr = 0;
    foreach ($lines as $line) 
    {
        if (totop($line, $top, $cnr) || empty($line)) continue;
        ++$inr;
        if (isset($checks["$cnr.$inr"])) $map["$top.$line"] = 1;
    }
    $lines = tolines($data);
    $checks = array();
    $cnr = 0;
    $inr = 0;
    foreach ($lines as $line) {
        if (totop($line, $top, $cnr) || empty($line)) continue;
        ++$inr;
        if (isset($map["$top.$line"])) $checks["$cnr.$inr"] = 1;
    }
    wchecks($checks);
}
else
{
    unlink($log);
}

file_put_contents($txt, $data);
go('/');
?>