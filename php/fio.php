<?php

    function txtFile()
    {
        global $uid;
        return "data/$uid.txt";
    }
    function logFile()
    {
        global $uid;
        return "data/$uid.log.json";
    }
    function dataFile()
    {
        global $uid;
        return "data/$uid.data.json";
    }

    function rTxt(&$txt)
    {
        $txt = NULL;
        $f = txtFile();
        $ok = file_exists($f);
        if ($ok) $txt = file_get_contents($f);
        return $ok;
    }

    function wTxt(&$txt)
    {
        file_put_contents(txtFile(), $txt);
    }

    function rDone(&$done)
    {
        $done = array();
        $f = logFile();
        $ok = file_exists($f);
        if ($ok) $done = json_decode(file_get_contents($f), true);
        return $ok;
    }

    function wDone(&$done)
    {
        $f = logFile();
        if (empty($done)) unlink($f);
        else file_put_contents($f, json_encode($done));
    }

    function wData(&$heads, &$items)
    {
        file_put_contents(dataFile(), json_encode(array($heads, $items)));
    }

    function rData(&$heads, &$items)
    {
        $heads = array();
        $items = array();
        $f = dataFile();
        $ok = file_exists($f);
        if ($ok) [$heads, $items] = json_decode(file_get_contents($f), true);
        return $ok;
    }

    function toItems($items)
    {
        foreach ($items as &$item)
        {
            $item = array_filter($item, function($i) { return !(empty($i) || is_array($i)); });
        }
        return $items;
    }

    function clean(&$txt)
    {
        $txt = trim(preg_replace('/^ *| *$/m', '', str_replace("\t", ' ', $txt)));
    }

    function txt2data(&$txt, &$heads, &$items)
    {
        clean($txt);
        $heads = array();
        $items = array();
        if (preg_match('/^.*?(@.+)/ms', $txt, $m))
        {
            $lines = preg_split('/\r?\n|\r/', htmlentities($m[1]));
            $lSet = false;
            $lOk  = false;
            $item = NULL;

            foreach ($lines as $line)
            {
                if (empty($line))
                {
                    $lSet = true;
                }
                elseif ($line[0] == '@')
                {
                    if (is_array($item)) $items[] = $item;
                    $item = array();
                    $heads[] = trim(substr($line, 1));
                    $lOk = false;
                }
                elseif ($line[0] == '#')
                {
                    preg_match('/^(#+) *(.*)/', $line, $m);
                    $item[] = array(strlen($m[1]) + 1, $m[2]);
                    $lOk = false;
                }
                else
                {
                    if ($lOk && $lSet) $item[] = '';
                    $item[] = $line;
                    $lSet = false;
                    $lOk = true;
                }
            }
            if (is_array($item)) $items[] = $item;
        }
    }
?>
