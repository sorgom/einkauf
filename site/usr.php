<?php
//  user register and current user instance
require_once('fnc.php');
require_once('tracer.php');

//  user register
class Register
{
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

    public function retrieve(string $uid, mixed &$hash)
    {
        $ret = false;
        $hash = '';
        if (isset($this->uids[$uid]))
        {
            $ret = true;
            $hash = $this->uids[$uid];
        }
        return $ret;
    }

    public function add(string $uid, string $pwd)
    {
        $this->uids[$uid] = password_hash($pwd, PASSWORD_BCRYPT);
    }

    public function remove(string &$uid)
    {
        unset($this->uids[$uid]);
    }
}

//  representation of current user
class Usr
{
    private string $uid = '';
    private array $params = [];
    private static string $sep = '-';
    private string $hash = '';
    private string $key = '';
    private bool $valid = false;

    public function set(string $uid)
    {
        $this->uid = $uid;
        $this->valid = reg()->retrieve($this->uid, $this->hash);
    }

    public function isValid() : bool
    {
        return $this->valid;
    }

    public function uid()
    {
        return $this->uid;
    }

    public function key()
    {
        return $this->key;
    }

    public function check()
    {
        if (!$this->valid) self::welcome();
        session_start();
        if (!(
            isset($_SESSION['uid']) &&
            isset($_SESSION['key']) &&
            $_SESSION['uid'] == $this->uid
        )) $this->login();
        $this->key = $_SESSION['key'];
    }

    public function ok()
    {
        $ok = $this->valid;
        if ($ok)
        {
            session_start();
            $ok = (
                isset($_SESSION['uid']) &&
                $_SESSION['uid'] == $this->uid
            );
        }
        return $ok;
    }

    public function checkPwd(string $pwd)
    {
        if (!hash_equals($this->hash, crypt($pwd, $this->hash))) $this->login();
    }

    public static function welcome()
    {
        header('Location: welcome.php');
        exit;
    }

    public function login()
    {
        $this->go('login');
    }

    public function go(string $php)
    {
        header("Location: $php.php?" . $this->uid);
        exit;
    }
    public function view(... $params)
    {
        header("Location: /?" . implode(self::$sep, [ $this->uid, ...$params]));
        exit;
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
        if ($this->uid)
            $this->valid = reg()->retrieve($this->uid, $this->hash);
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
