<?php
namespace fnc;

    function isi(string $c) { return !(empty($c) || $c[0] == '#'); }

    function impl($a) { return implode("\n", $a); }

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

    function dtxt($name)
    {
        $dir = 'txt';
        foreach([
            "$dir/$name.txt",
            "$dir/$name.default.txt"
        ] as $file)
        {
            if (file_exists($file)) return trim(file_get_contents($file));
        }
        return '';
    }

    function split($rx, &$text, &$keys, &$data, &$rem)
    {
        clean($txt, $text);
        $ok = preg_match_all($rx, $txt, $m);
        if ($ok)
        {
            $keys = $m[1];
            $data = array_map('trim', preg_split($rx, $txt));
            $rem = array_shift($data);
        }
        return $ok;
    }
?>
