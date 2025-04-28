<?php
require_once('usr.php');
if (usr()->valid())
{
    require_once('data.php');
    states()->set($_POST['st'], $_POST['id']);
    states()->save();
}
?>
