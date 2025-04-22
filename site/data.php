<?php

    function chkData()
    {
        if (!is_dir('data')) mkdir('data');
    }

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
        $txt = '';
        $f = txtFile();
        $ok = file_exists($f);
        if ($ok) $txt = file_get_contents($f);
        return $ok;
    }

    function wTxt(&$txt)
    {
        chkData();
        file_put_contents(txtFile(), $txt);
    }

    function rStates(&$states)
    {
        $states = array();
        $f = logFile();
        $ok = file_exists($f);
        if ($ok) $states = json_decode(file_get_contents($f), true);
        return $ok;
    }

    function wStates(&$states)
    {
        if (empty($states)) dStates();
        else {
            chkData();
            file_put_contents(logFile(), json_encode($states));
        }
    }

    function dStates()
    {
        $f = logFile();
        if (file_exists($f)) unlink($f);
    }

    function wData(&$heads, &$items)
    {
        chkData();
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

    //  valid item line
    function isl($c)
    {
        return !(empty($c) || $c[0] == '#');
    }

    //  explode for map
    function xpl($a) { return explode("\n", $a); }

    //  implode
    function impl($a) { return implode("\n", $a); }

    // remove more than double line spaces
    function despace(&$txt)
    {
        $txt = preg_replace('/\n{3,}/', "\n\n", $txt);
    }

    //  filter valid items
    function toItems($items)
    {
        foreach ($items as &$item)
        {
            $item = array_values(array_filter($item, 'isl'));
        }
        return $items;
    }

    function clean(&$txt)
    {
        $txt = trim(preg_replace('/^ *| *$/m', '', preg_replace('/\r\n|\r/', "\n", str_replace("\t", ' ', $txt))));
        despace($txt);
    }

    function addNfd(&$txt, $nfd)
    {
        if (!empty($nfd))
            $txt = trim($txt) . "\n\n@ ...\n" . impl(array_filter(array_unique($nfd), 'isl'));
    }

    function txt2data(&$txt, &$heads, &$items)
    {
        $heads = array();
        $items = array();
        if (preg_match('/^.*?(@.+)/ms', $txt, $m))
        {
            $lines = explode("\n", htmlentities($m[1]));
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
                    preg_match('/^#+ *(.*)/', $line, $m);
                    $item[] = "#$m[1]";
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

    function save(&$txt, $skip=false)
    {
        txt2data($txt, $heads, $items);

        if (file_exists(logFile()) && file_exists(dataFile()))
        {
            rStates($states);
            rData($heads1, $items1);
            $items1 = toItems($items1);
            $map = array();
            foreach ($items1 as $cnr => $item)
            {
                $head = $heads1[$cnr];
                if ($skip && $head == '...') continue;
                foreach ($item as $inr => $i)
                {
                    $c = "$cnr.$inr";
                    if (isset($states[$c])) $map["$head.$i"] = $states[$c];
                }
            }
            $items2 = toItems($items);
            $states = array();
            foreach ($items2 as $cnr => $item)
            {
                $head = $heads[$cnr];
                $all = true;
                foreach ($item as $inr => $i)
                {
                    $c = "$head.$i";
                    if (isset($map[$c])) $states["$cnr.$inr"] = $map[$c];
                    else $all = false;
                }
                if ($all) $states[$cnr] = 1;
            }
            wStates($states);
        }
        else dStates();
        wData($heads, $items);
        wTxt($txt);
    }
?>
