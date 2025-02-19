<?php 
    require_once('usr.php');
    if ($_GET)
    {
        $x = array_keys($_GET)[0];
        if (isusr($x)) 
        {
            session_start();
            $_SESSION['usr'] = $x;
            go('view');
        }
    }
    go('new');
?>
