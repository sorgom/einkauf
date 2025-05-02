<?php

class Register
{
    private static string $dir  = 'data';
    private static string $file = 'data/reg.json';
    private static string $log  = 'data/.log';
    private array $uids = [];

    public function __construct(bool $load=false)
    {
        if ($load) $this->load();
    }
    public function save()
    {
        self::check();
        file_put_contents(self::$file, json_encode($this->uids));
    }
    public function load()
    {
        $ok = file_exists(self::$file);
        if ($ok) $this->uids = json_decode(file_get_contents(self::$file), true);
        return $ok;
    }

    public function has(string $uid)
    {
        return isset($this->uids[$uid]);
    }

    public function add(string $uid)
    {
        return $this->uids[$uid] = 1;
    }

    public function remove(string &$uid)
    {
        unset($this->uids[$uid]);
    }

    public static function instance()
    {
        static $instance = new Register(true);
        return $instance;
    }
    private static function check()
    {
        if (!is_dir(self::$dir)) mkdir(self::$dir);
    }
    public static function log(string $message)
    {
        self::check();
        error_log($message . "\n", 3, self::$log);
    }
}

class Usr
{
    private string $uid = '';
    private bool $valid = false;
    private array $params = [];

    public static function instance()
    {
        static $instance = new Usr();
        return $instance;
    }

    public function set(string $uid)
    {
        $this->uid = $uid;
    }

    public function valid()
    {
        return $this->valid;
    }

    public function uid()
    {
        return $this->uid;
    }

    public function check()
    {
        if (!$this->valid) header('Location: hello.php');
    }

    public function go(string $php)
    {
        header("Location: $php.php?" . $this->uid);
    }
    public function view($cnr=NULL)
    {
        header("Location: /?" . $this->uid . (is_null($cnr) ? '' : "&$cnr"));
    }

    function param(int $n=0)
    {
        return count($this->params) > $n ? $this->params[$n] : NULL;
    }

    function params()
    {
        return $this->params;
    }

    private function __construct()
    {
        if ($_POST)
        {
            $this->uid = $_POST['uid'];
        }
        else if ($_GET)
        {
            $this->params = array_keys($_GET);
            $this->uid = array_shift($this->params);
        }
        if ($this->uid)
        {
            $this->valid = reg()->has($this->uid);
        }
    }
}

function usr()
{
    return Usr::instance();
}
function reg()
{
    return Register::instance();
}
?>
