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
    b_base('reset', $cnr , ' ', 'reset');
}

function b_top()
{
    b_navi('', ' ', 'menu');
}

function b_chap($cnr, $ttl)
{
    b_navi($cnr, $ttl, 'chap');
}

function b_input()
{
    b_base('input', '', ' ', 'prev');
}   

function b_prev_cancel()
{
    b_base('data', 'C', ' ', 'nok');
}

function b_prev_write()
{
    b_base('data', 'W', ' ', 'ok');
}

function b_reg_go()
{
    b_base('register', '', 'anmelden', 'ok');
}

function b_new_go($nu)
{
    b_base('index', $nu, 'start', 'ok');
}

?>