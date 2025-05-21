<?php
require_once('usr.php');
require_once('tracer.php');
[$uid, $task, $data] = json_decode(file_get_contents('php://input'), true);
usr()->set($uid);
$res = 'NOK';
if (usr()->ok())
{
    require_once('data.php');
    switch ($task)
    {
        case 'reset':
            $cnr = $data;
            // trace(['reset', $cnr]);
            states()->reset($cnr);
            states()->save();
            $res = 'OK';
            break;
        case 'state':
            [ $cnr, $inr, $cc, $ci ] = $data;
            // trace(['state', $cnr, $inr, $cc, $ci]);
            states()->set($cc, $cnr);
            states()->set($ci, $cnr, $inr);
            states()->save();
            $res = 'OK';
            break;
    }
}
echo $res;
?>
