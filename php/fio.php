<?php
    function tolines($data)
    {
        return explode(PHP_EOL, preg_replace('/^ +/m', '', preg_replace('/^.*?#/s', '#', $data)));
    }
    function getlines()
    {
        global $txt;
        return tolines(file_get_contents($txt));
    }
    function getchecks()
    {
        global $log;
        $checks = array();
        if (file_exists($log)) $checks = json_decode(file_get_contents($log), true);
        return $checks;
    }
    function wchecks($checks)
    {
        global $log;
        file_put_contents($log, json_encode($checks));
    }
    function totop($line, &$top, &$cnr)
    {
        $res = preg_match('/^(#+) *(.*)/', $line, $t);
        $lvl = 0;
        if ($res) {
            $top = $t[2];
            $lvl = strlen($t[1]);
            if ($lvl == 1) ++$cnr;
        }
        return $lvl;
    }
?>  