<?php
require_once('view.php');
require_once('fnc.php');
$src = 'imprint.txt';
if (!fnc\load($txt, $rc)) $txt = 'No imprint provided.';
jsView('Imprint', '', $txt);

?>
