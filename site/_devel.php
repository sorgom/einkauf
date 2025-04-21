<?php

require_once('_states.php');
require_once('_elements.php');

$uid = 'abcdefg';

$c = new Item(5, 'wumpel');
$m = new Menu();
for ($i = 0; $i < 10; ++$i)
{
    $m->add($i, "Entry $i");
}
$m->say();
$c->say();
?>
