<!DOCTYPE html>
<html lang=en>
<head>
<title>todo</title>
<meta charset='UTF-8'>
<link rel=stylesheet href='site.css'>
<link rel=icon type='image/gif' href='img/check_icon.svg'>
</head><body>
<?php
    function b_base($dest, $what, $title, $type = false)
    {
        global $uid;
        $w = $what ? "&$what" : '';
        echo "<a href=$dest?$uid$w class='bt $type'>$title</a>\n";
    }

    function b_reset($cnr)
    {
        b_base('reset.php', $cnr , ' ', 'reset');
    }

    function b_top($ttl, $checked=false)
    {
        global $uid;
        $cl = $checked ? ' x' : '';
        echo "<div class=fixed><a id=state href=/?$uid class='bt state$cl'>$ttl</a></div>\n";
    }

    function b_chap($cnr, $ttl, $done = NULL)
    {
        b_base('/', $cnr, $ttl, 'chap' . ($done ? ' x' : ''));
    }

    function bt_chap_done($cnr)
    {
        echo "<a id=$cnr onclick='chapDone(this)' class='bt done'> </a>\n";
    }

    function b_edit()
    {
        b_base('input.php', '', ' ', 'edit');
    }

    function b_prev_cancel()
    {
        b_base('data.php', 'C', ' ', 'cancel');
    }

    function b_prev_write()
    {
        b_base('data.php', 'W', ' ', 'save');
    }

    function b_reg_go()
    {
        b_base('register.php', '', 'sign in', 'ok');
    }

    function b_new_go()
    {
        b_base('/', '', 'start', 'ok');
    }

    function b_logout()
    {
        b_base('logout.php', '', ' ', 'logout');
    }

    function b_login()
    {
        b_base('/', '', ' ', 'login');
    }

    function b_remove($cnr, $second=false)
    {
        b_base('remove.php', $second ? "$cnr&1" : $cnr, ' ', 'remove');
    }

    function b_remove_confirm($cnr, $ttl)
    {
        b_base('remove.php', "$cnr&X", "$ttl ?", 'remove confirm');
    }

    function b_back()
    {
        echo "<a href=javascript:history.back() class='bt back'> </a>\n";
    }
?>
