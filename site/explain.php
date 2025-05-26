<?php
//  display explanation
//  TODO: find a multi language
require_once('view.php');
require_once('fnc.php');
$txt = 'Error 505';
fnc\load($txt, 'explain.txt');
jsView('Explain', '', $txt);
?>
