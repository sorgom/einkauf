<?php
    require_once('usr.php');
    usr()->check();
    $cnr = usr()->param();

    var_dump(usr()->params());

    if (count(usr()->params()) < 2)
    {
        require_once('view.php');
        new RemoveConfirm($cnr);
        new Back();
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
