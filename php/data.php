<?php
    session_start();
    require_once('usr.php');

    $data = NULL;
    if ($_POST)
    {
        $uid = $_POST['uid'];
        if (isset($_POST['cancel']))
        {
            unset($_SESSION['data']);
            goView();
        }

        if (empty($_POST['data'])) go('input.php');

        if (isset($_POST['prev']))
        {
            $_SESSION['data'] = $_POST['data'];
            goView();
        }
        $data = &$_POST['data'];
    }
    else
    {
        setUid();
        $x = getParam();
        switch($x)
        {
        case 'C':
            unset($_SESSION['data']);
            goView();
            break;
        case 'W':
            $data = &$_SESSION['data'];
            break;
        default:
            goView();
        }
    }

    require_once('fio.php');
    txt2data($data, $heads, $items);

    if (file_exists(logFile()) && file_exists(dataFile()))
    {
        rDone($done);
        rData($heads1, $items1);
        $items1 = toItems($items1);
        $map = array();
        $cnr = 0;
        foreach ($items1 as $item)
        {
            $head = $heads1[$cnr];
            echo "head: $head\n";
            ++$cnr;
            $inr = 0;
            foreach ($item as $i)
            {
                ++$inr;
                if (isset($done["$cnr.$inr"])) $map["$head.$i"] = 1;
            }
        }
        $items2 = toItems($items);
        $done = array();
        $cnr = 0;
        foreach ($items2 as $item)
        {
            $head = $heads[$cnr];
            ++$cnr;
            $inr = 0;
            $all = true;
            foreach ($item as $i)
            {
                ++$inr;
                if (isset($map["$head.$i"])) $done["$cnr.$inr"] = 1;
                else $all = false;
            }
            if ($all) $done[$cnr] = 1;
        }
        wDone($done);
    }
    else
    {
        unlink(logFile());
    }
    wData($heads, $items);
    wTxt($data);
    unset($_SESSION['data']);
    goView();
?>
