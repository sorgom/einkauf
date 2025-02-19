<?php

function b_base($dest, $what, $title, $type = false)
{
    $t = $type ? "bt $type" : 'bt';
    $w = $what ? "?$what" : '';
    echo "<a href=$dest.php$w class='$t'>$title</a>\n";
}

function b_navi($what, $title, $type = false)
{
    b_base('navi', $what, $title, $type);
}

function b_reset($cnr)
{
    b_base('reset', $cnr , 'Zurücksetzen', 'reset');
}

function b_top()
{
    b_navi('', 'Menü', 'menu');
}

function b_chap($cnr, $ttl)
{
    b_navi($cnr, $ttl, 'chap');
}

function b_input()
{
    b_base('input', '', 'Zur Eingabe', 'ok');
}   

function b_prev_cancel()
{
    b_base('data', 'C', 'Abbruch', 'nok');
}

function b_prev_write()
{
    b_base('data', 'W', 'OK', 'ok');
}
?>