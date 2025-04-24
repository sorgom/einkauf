<?php
require_once('TxtObject.php');
usr()->check();
$cnr = usr()->param();
if (!is_null($cnr))
{
    states()->reset($cnr);
    states()->save();
}
usr()->view($cnr);
?>
