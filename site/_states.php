<?php
//  background states tracker called by JSON encoded ajax requests
require_once('usr.php');
[$uid, $task, $data] = json_decode(file_get_contents('php://input'), true);
usr()->set($uid);
$res = 'NOK';
if (usr()->ok())
{
    require_once('data.php');
    switch ($task)
    {
        //  reset of chapter call
        case 'reset':
            $cnr = $data;
            states()->reset($cnr);
            states()->save();
            $res = 'OK';
            break;
        //  item click state change call
        case 'state':
            [ $cnr, $inr, $cc, $ci ] = $data;
            states()->set($cc, $cnr);
            states()->set($ci, $cnr, $inr);
            states()->save();
            $res = 'OK';
            break;
    }
}
echo $res;
?>
