<?php
require_once('fnc.php');

class Logger
{
    private static string $file = 'data/.log';
    public function log(mixed $data)
    {
        $str = var_export($data, true) . "\n";
        fnc\save(self::$file, $str, FILE_APPEND);
    }
}

function logger()
{
    static $instance = new Logger();
    return $instance;
}

?>
