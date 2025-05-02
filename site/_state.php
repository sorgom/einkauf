<?php
require_once('usr.php');
if (usr()->valid())
{
    require_once('data.php');
    states()->set(... usr()->params());
    states()->save();
}
?>
