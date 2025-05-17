<?php
declare(strict_types=1);

namespace fnc;

    function isi(string $c) { return !(empty($c) || $c[0] == '#'); }

    function impl(array $a) { return implode("\n", $a); }

    function expl(string $a) { return explode("\n", $a); }

    function save(string $file, string &$cont, int $flags = 0)
    {
        $dir = dirname($file);
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($file, $cont, $flags);
    }

    function load(mixed &$cont, string $file)
    {
        $ok = is_file($file);
        if ($ok) $cont = file_get_contents($file);
        return $ok;
    }

    function clean(mixed &$trg, string &$src)
    {
        $trg = trim(preg_replace('/  +/', ' ', preg_replace('/^ *| *$/m', '', preg_replace('/\r\n|\r/', "\n", str_replace("\t", ' ', $src)))));
    }

    function clearSession()
    {
        if (session_status() == PHP_SESSION_ACTIVE) session_destroy();
    }
?>
