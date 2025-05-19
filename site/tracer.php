<?php
require_once('fnc.php');

class Tracer
{
    private static string $file = 'data/.trace';
    public function trace(mixed ...$data)
    {
        $res = [];
        foreach($data as $d)
        {
            $res[] = var_export($d, true);
        }
        $res[] = '';
        $str = fnc\impl($res);
        fnc\save(self::$file, $str, FILE_APPEND);
    }
}

function trace(mixed ...$data)
{
    static $instance = new Tracer();
    $instance->trace(...$data);
}

?>
