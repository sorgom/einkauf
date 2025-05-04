<?php
require_once('usr.php');
if (usr()->valid())
{
    require_once('data.php');
    states()->reset(usr()->param());
    states()->save();
}
?>
