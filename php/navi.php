<?php
session_start();
require_once('usr.php');
setusr();
unset($_SESSION['chap']);
$x = getparam();
if (preg_match('/^\d+$/', $x))
{
    $_SESSION['chap'] = (int) $x;
}
go('view');
?>