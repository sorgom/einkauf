<?php
require_once('view.php');
require_once('fnc.php');
if (!fnc\load($txt, 'imprint.txt')) $txt = 'No imprint provided.';
$branch = 'dev';
$date = 'NN';
if (fnc\load($info, 'commit.txt')) [$branch, $date] = explode(',', $info, 2);
$data = [$txt, $branch, $date];
jsView('Imprint', '', $data);

?>
