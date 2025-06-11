<?php
//  static functions collection
declare(strict_types=1);

namespace fnc;

    //  text line describes an item (no blank line / no heading)
    function isi(string $c) { return !(empty($c) || $c[0] == '#'); }

    //  implode with line break
    function impl(array $a) { return implode("\n", $a); }

    //  explode with line break
    function expl(string $a) { return explode("\n", $a); }

    //  save with directory check
    function save(string $file, string &$cont, int $flags = 0)
    {
        $dir = dirname($file);
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($file, $cont, $flags);
    }

    //  load with file check
    function load(mixed &$cont, string $file)
    {
        $ok = is_file($file);
        if ($ok) $cont = file_get_contents($file);
        return $ok;
    }

    //  clean a text for application of regexes
    function clean(mixed &$trg, string &$src)
    {
        $trg = trim(preg_replace('/  +/', ' ', preg_replace('/^ *| *$/m', '', preg_replace('/\r\n|\r/', "\n", str_replace("\t", ' ', $src)))));
    }

    //  terminate session if active
    function clearSession()
    {
        session_start();
        $_SESSION = array();

        if (ini_get('session.use_cookies'))
        {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }

    //  generate encryption / decryption key from password
    function key(string $pwd)
    {
        return hash('sha256', $pwd);
    }

    function protocol()
    {
        $isSecure =
            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ||
            (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on');
        return $isSecure ? 'https' : 'http';
    }
?>
