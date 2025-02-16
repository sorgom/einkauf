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
    if ($_GET)
    {
        $usr = array_keys($_GET)[0];
    }
    elseif ($_POST) {
        $usr = $_POST['usr'];
        if (array_key_exists('isnew', $_POST)) addusr();
    }
}

function checkusr()
{
    global $usr, $txt, $log;
    getusr();
    $reg = getreg();
    if (!array_key_exists($usr, $reg)) 
    { 
        header('Location: new.php');
        exit;
    }
    $txt = "data/$usr.txt";
    $log = "data/$usr.json";
}

function usrtag()
{
    global $usr;
    echo "\n<input type=hidden name=usr value=$usr>\n";
}

function go($page)
{
    global $usr;
    header("Location: $page?$usr");
    exit;
}
?>