<?php
    require_once('data.php');
    usr()->check();
    $cnr = usr()->param();
    if (!is_null($cnr))
    {
        data()->remove($cnr);
        data()->save();
    }
    usr()->view();
?>
