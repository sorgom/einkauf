<?php
require_once('UsrObjects.php');

require_once('OutputObjects.php');

if (!Usr::instance()->valid()) exit();

Register::instance()->add('ABC');

$c = new Item(12, 4, 'wumpel');
new Menu();
$c->html();
new ItemList(1);
?>
