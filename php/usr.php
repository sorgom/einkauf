<?php

$usr = NULL;
$params = array();

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
    global $usr, $params;
    if ($_GET)
    {
        $params = array_keys($_GET);
        $usr = array_shift($params);
    }
    usrfiles();
}

function checkusr()
{
    global $usr;
    if (!(isset($_SESSION['usr']) && $usr == $_SESSION['usr']))
    {
        if (isusr($usr))
        {
            $_SESSION['usr'] = $usr;
        }
        else gonew();
    }
}

function getparam()
{
    global $params;
    return array_shift($params);
}

function go($dest, $param=NULL)
{
    global $usr;
    header("Location: $dest?$usr" . ($param ? "&$param" : ''));
    exit;
}

function goview($param=NULL)
{
    global $usr;
    go('/', $param);
}

function gonew()
{
    if (session_status() == PHP_SESSION_ACTIVE)
    {
        session_destroy();
        header('Location: new.php');
        exit;
    }
}

function usrtag()
{
    global $usr;
    echo "<input type=hidden name=usr value=$usr>\n";
}
?>
