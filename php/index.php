<?php 
    if ($_GET)
    {
        require_once('usr.php');
        $x = array_keys($_GET)[0];
        if (isusr($x)) 
        {
            session_start();
            $_SESSION['usr'] = $x;
            go('view');
        }
    }
    session_destroy();
    go('new');
?>
