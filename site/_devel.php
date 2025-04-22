<?php

require_once('OutputObjects.php');

$uid = 'A321863A';

$c = new Item(12, 4, 'wumpel');
new Menu();
$c->html();
new ItemList(1);
?>
