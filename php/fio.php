<?php
    function tolines($data)
    {
        $res = array();
        if (preg_match('/^ *# /m', $data))
            $res = preg_split('/\r?\n|\r/', preg_replace('/^ +/m', '', preg_replace('/^(?:\s*|.*?\n)# /s', '# ', $data)));
        return $res;
    }
    function getlines()
    {
        global $txt;
        if (file_exists($txt)) return tolines(file_get_contents($txt));
        return array();
    }

    function getdone()
    {
        global $log;
        $done = array();
        if (file_exists($log)) $done = json_decode(file_get_contents($log), true);
        return $done;
    }

    function wdone($done)
    {
        global $log;
        file_put_contents($log, json_encode($done));
    }

    function totop($line, &$top, &$cnr, &$inr)
    {
        $res = preg_match('/^(#+) *(.*)/', $line, $t);
        $lvl = 0;
        if ($res) {
            $top = $t[2];
            $lvl = strlen($t[1]);
            if ($lvl == 1)
            {
                ++$cnr;
                $inr = 0;
            }
        }
        return $lvl;
    }
?>
