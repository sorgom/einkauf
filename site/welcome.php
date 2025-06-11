<?php
require_once('view.php');
require_once('fnc.php');
fnc\clearSession();
$data = [];
jsView('WelcomeForm', '', $data);
?>
