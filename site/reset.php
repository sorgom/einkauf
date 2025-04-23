<?php
require_once('DataObjects.php');
Usr::instance()->check();
$cnr = Usr::instance()->param();
if (!is_null($cnr))
{
    States::instance()->reset($cnr);
    States::instance()->save();
}
Usr::instance()->view($cnr);
?>
