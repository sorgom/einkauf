<?php
require_once('fnc.php');

class Logger
{
    private static string $file = 'data/.log';
    public function log(mixed ...$data)
    {
        $res = [];
        foreach($data as $d)
        {
            $res[] = var_export($data, true);
        }
        $res[] = '';
        $str = fnc\impl($res);
        fnc\save(self::$file, $str, FILE_APPEND);
    }
}

function logger()
{
    static $instance = new Logger();
    return $instance;
}

?>
