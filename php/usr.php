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

function addusr()
{
    global $usr;
    $reg = getreg();
    $reg[$usr] = 1;
    file_put_contents(regf(), json_encode($reg));
}

function getusr()
{
    global $usr;
    $usr = $_SESSION['usr'];
    setusr();
}

function isusr($x)
{
    $reg = getreg();
    return isset($reg[$x]);
}

function setusr()
{
    global $usr, $txt, $log;
    $txt = "data/$usr.txt";
    $log = "data/$usr.json";
}

function checkusr($cand)
{
    global $usr;
    $reg = getreg();
    if (isset($reg[$cand])) 
    {
        $usr = $cand;
        $_SESSION['usr'] = $usr;
    }
    else
    { 
        header('Location: new.php');
        exit;
    }
}

function usrtag()
{
    global $usr;
    echo "<input type=hidden name=usr value=$usr>\n";
}

function go($page)
{
    header("Location: $page");
    exit;
}
?>