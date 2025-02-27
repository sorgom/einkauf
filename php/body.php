<!DOCTYPE html>
<html lang=de>
<head>
<title>todo</title>
<meta charset='UTF-8'>
<link rel=stylesheet href='site.css'>
<link rel=icon type='image/gif' href='img/check_icon.svg'>
</head><body>
<?php
    function b_base($id, $dest, $param=NULL)
    {
        global $uid;
        $p = $param ? "&$param" : '';
        echo "<a id=$id href=$dest?$uid$p> </a>\n";
    }

    function b_reset($cnr)
    {
        b_base('reset', 'reset.php', $cnr);
    }

    function b_top($ttl, $checked=false)
    {
        global $uid;
        echo "<div id=top><a id=state href=/?$uid" . ($checked ? ' class=x' : '') . ">$ttl</a></div>\n";
    }

    function b_chap($cnr, $ttl, $done = NULL)
    {
        global $uid;
        $cl = $done ? ' x' : '';
        echo "<a href=/?$uid&$cnr class='chap$cl'>$ttl</a>\n";
    }

    function b_edit()
    {
        b_base('edit', 'input.php');
    }

    function b_prev_cancel()
    {
        b_base('cancel', 'data.php', 'C');
    }

    function b_prev_write()
    {
        b_base('save', 'data.php', 'W');
    }

    function b_new_go()
    {
        b_base('register', 'register.php');
    }

    function b_reg_go()
    {
        b_base('start', '/');
    }

    function b_logout()
    {
        b_base('logout', 'logout.php');
    }

    function b_login()
    {
        b_base('login', '/');
    }

    function b_remove($cnr)
    {
        b_base('remove', 'remove.php', $cnr);
    }

    function b_remove_confirm($cnr, $ttl)
    {
        global $uid;
        echo "<a id=remove_confirm href=remove.php?$uid&$cnr&X>$ttl ?</a>\n";
    }

    function b_back()
    {
        echo "<a id=back href=javascript:history.back()> </a>\n";
    }
?>
