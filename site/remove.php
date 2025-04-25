<?php
    require_once('usr.php');
    usr()->check();
    $cnr = usr()->param();

    if (count(usr()->params()) < 2)
    {
        require_once('view.php');
        new Back();
        new RemoveConfirm($cnr);
        ?></body></html><?php
    }
    else
    {
        require_once('data.php');
        data()->remove($cnr);
        data()->save();
        usr()->view();
    }
?>
