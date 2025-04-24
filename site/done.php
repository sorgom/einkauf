<?php
$x = $_POST['uid'];
$id = $_POST['id'];
$ck = $_POST['ck'];

require_once('usr.php');
if (usr()->valid())
{
    require_once('data.php');
    states()->set($ck, $id);
    states()->save();
}
?>
