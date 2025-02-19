<?php
    session_start();
    require_once('usr.php');
    setusr();

    $data = '';
    if ($_GET)
    {
        $x = getparam();
        switch($x)
        {
        case 'C': 
            unset($_SESSION['data']);
            go('view');
            break;
        case 'W': 
            $data = $_SESSION['data'];
            break;
        default: 
            go('view');
        }
    }
    elseif ($_POST) 
    {
        if (isset($_POST['cancel'])) 
        {
            unset($_SESSION['data']);
            go('view');
        }

        $data = trim($_POST['data']);
        if (empty($data)) go('input');

        if (isset($_POST['prev'])) 
        {   
            $_SESSION['data'] = $data;
            go('view');
        }
    }
    else go('view');

    if (file_exists($log) && file_exists($txt))
    {
        require_once('fio.php');
        $lines = getlines();
        $checks = getchecks();
        $top = '';
        $map = array(); 
        $cnr = 0;
        $inr = 0;
        foreach ($lines as $line) 
        {
            if (totop($line, $top, $cnr, $inr) || empty($line)) continue;
            ++$inr;
            if (isset($checks["$cnr.$inr"])) $map["$top.$line"] = 1;
        }
        $lines = tolines($data);
        $checks = array();
        $cnr = 0;
        $inr = 0;
        foreach ($lines as $line) {
            if (totop($line, $top, $cnr, $inr) || empty($line)) continue;
            ++$inr;
            if (isset($map["$top.$line"])) $checks["$cnr.$inr"] = 1;
        }
        wchecks($checks);
    }
    else
    {
        unlink($log);
    }

    file_put_contents($txt, $data);
    unset($_SESSION['data']);
    unset($_SESSION['chap']);
    go('view');
?>