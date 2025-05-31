<?php
//  remove a user todo list
require_once('data.php');
usr()->check();
$lnr = usr()->param();
if (!is_null($lnr)) data()->remove($lnr);
usr()->view();
?>
