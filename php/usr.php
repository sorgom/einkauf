<?php

function regf()
{
    return 'data/reg.json';
}       


function getreg()
{
    $rf = regf();
    if (file_exists($rf))
        return json_decode(file_get_contents($rf), true);
    return array();
}

function isusr($x)
{
    $reg = getreg();
    return isset($reg[$x]);
}

function usrfiles()
{
    global $usr, $txt, $log;
    $txt = "data/$usr.txt";
    $log = "data/$usr.json";
}

function setusr()
{
    global $usr;
    if (!isset($_SESSION['usr'])) 
    {
        session_destroy();
        go('new');
    }
    $usr = $_SESSION['usr'];
    usrfiles();
}

function sval($key, $def = false)
{
    return isset($_SESSION[$key]) ? $_SESSION[$key] : $def;
}

function getparam()
{
    return $_GET ? array_keys($_GET)[0] : '';
}

function go($php)
{
    header("Location: $php.php");
    exit;
}
?>