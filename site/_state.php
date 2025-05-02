<?php
require_once('usr.php');
if (usr()->valid())
{
    $id = $_POST['id'];
    $st = $_POST['st'];
    require_once('data.php');
    states()->set($st, $id);
    states()->save();
}
?>
