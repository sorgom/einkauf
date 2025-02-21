<?php
    function b_base($dest, $what, $title, $type = false)
    {
        global $usr;
        $t = $type ? "bt $type" : 'bt';
        $w = $what ? "&$what" : '';
        echo "<a href=$dest?$usr$w class='$t'>$title</a>\n";
    }

    function b_reset($cnr)
    {
        b_base('reset.php', $cnr , ' ', 'reset');
    }

    function b_top()
    {
        b_base('/', '', ' ', 'menu');
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
        b_base('register.php', '', 'anmelden', 'ok');
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
?>
