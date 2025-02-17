<?php
require_once("usr.php");
checkusr();
unlink($log);
go('/');
?>
