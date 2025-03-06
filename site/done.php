<?php
$x = $_POST['uid'];
$id = $_POST['id'];
$ck = $_POST['ck'];

require_once('usr.php');
if (isUid($x))
{
    $uid = $x;
    require_once('fio.php');

    rDone($done);
    if ($ck == '1') $done[$id] = 1;
    else
    {
        unset($done[$id]);
        if (preg_match('/^(\d+)\./', $id, $m))
        {
            unset($done[$m[1]]);
        }
    }
    wDone($done);
}
?>
