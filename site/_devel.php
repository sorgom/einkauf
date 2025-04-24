<?php
require_once('usr.php');

require_once('OutputObjects.php');

if (!usr()->valid()) exit();

Register::instance()->add('ABC');

$c = new Item(12, 4, 'wumpel');
new Menu();
$c->html();
new ItemList(1);
?>
