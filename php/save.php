<?php
require_once("usr.php");
checkusr();
if (isset($_POST['skip'])) go('/');

$data = trim($_POST['data']);
if (empty($data)) go('input.php');

file_put_contents($txt, $data);
unlink($log);
go('/');
?>