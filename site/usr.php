<?php
require_once('fnc.php');
class Register
{
    private static string $dir  = 'data';
    private static string $file = 'data/reg.json';
    private array $uids = [];

    public function __construct(bool $load=false)
    {
        if ($load) $this->load();
    }
    public function save()
    {
        $json = json_encode($this->uids);
        fnc\save(self::$file, $json);
    }
    public function load()
    {
        if (fnc\load($json, self::$file))
            $this->uids = json_decode($json, true);
        else $this->uids = [];
    }

    public function has(string $uid)
    {
        return isset($this->uids[$uid]);
    }

    public function check(string $uid, string $pwd)
    {
        $ret = false;
        if ($this->has($uid))
        {
            $val = $this->uids[$uid];
            $ret = gettype($val) == 'string' ?
                hash_equals($val, crypt($pwd, $val)) : true;
        }
        return $ret;
    }

    public function add(string $uid, string $pwd='')
    {
        if ($pwd)
            $this->uids[$uid] = password_hash($pwd, PASSWORD_BCRYPT);
        else
            $this->uids[$uid] = 0;
    }

    public function remove(string &$uid)
    {
        unset($this->uids[$uid]);
    }
}

class Usr
{
    private string $uid = '';
    private array $params = [];
    private static string $sep = '-';


    public function set(string $uid)
    {
        $this->uid = $uid;
    }

    public function valid()
    {
        return reg()->has($this->uid);
    }

    public function uid()
    {
        return $this->uid;
    }

    public function check()
    {
        if (!$this->valid()) $this->welcome();
    }
    public static function welcome()
    {
        header('Location: welcome.php');
    }

    public function go(string $php)
    {
        header("Location: $php.php?" . $this->uid);
    }
    public function view(... $params)
    {
        header("Location: /?" . implode(self::$sep, [ $this->uid, ...$params]));
    }

    public function param(int $n=0)
    {
        return count($this->params) > $n ? $this->params[$n] : NULL;
    }

    public function params()
    {
        return $this->params;
    }

    public function __construct()
    {
        if ($_POST)
        {
            $this->uid = $_POST['uid'];
        }
        else if ($_GET)
        {
            $ps = array_keys($_GET);
            if ($ps)
            {
                $this->params = explode(self::$sep, $ps[0]);
                $this->uid = array_shift($this->params);
            }
        }
    }
}

function usr()
{
    static $instance = new Usr();
    return $instance;
}

function reg()
{
    static $instance = new Register(true);
    return $instance;
}
?>
