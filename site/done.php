<?php
$x = $_POST['uid'];
$id = $_POST['id'];
$ck = $_POST['ck'];

require_once('usr.php');
if (isUid($x))
{
    $uid = $x;
    require_once('data.php');

    rStates($states);

    if (! $ck)
    {
        unset($states[$id]);
        if (preg_match('/^(\d+)\./', $id, $m))
        {
            unset($states[$m[1]]);
        }
    }
    else $states[$id] = $ck;
    wStates($states);
}
?>
