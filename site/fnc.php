<?php
namespace fnc;

    function isi(string $c) { return !(empty($c) || $c[0] == '#'); }

    function impl($a) { return implode("\n", $a); }

    function expl($a) { return explode("\n", $a); }

    function save($file, string &$cont)
    {
        $dir = dirname($file);
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($file, $cont);
    }

    function load(&$cont, $file)
    {
        $ok = is_file($file);
        if ($ok) $cont = file_get_contents($file);
        return $ok;
    }

    function clean(&$trg, &$src)
    {
        $trg = trim(preg_replace('/  +/', ' ', preg_replace('/^ *| *$/m', '', preg_replace('/\r\n|\r/', "\n", str_replace("\t", ' ', $src)))));
    }

?>
