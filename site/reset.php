<?php
require_once('usr.php');
usr()->check();
$cnr = usr()->param();
if (!is_null($cnr))
{
    require_once('data.php');
    states()->reset($cnr);
    states()->save();
}
usr()->view($cnr);
?>
