<?php
$x = $_POST['uid'];
$id = $_POST['id'];
$ck = $_POST['ck'];

require_once('UsrObjects.php');
if (usr()->valid())
{
    require_once('DataObjects.php');
    states()->set($ck, $id);
    states()->save();
}
?>
