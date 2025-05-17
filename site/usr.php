<?php
require_once('fnc.php');
require_once('logger.php');
//  user register
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

    public function retrieve(string $uid, mixed &$hash)
    {
        $ret = false;
        $hash = NULL;
        if (isset($this->uids[$uid]))
        {
            $ret = true;
            $val = $this->uids[$uid];
            if (gettype($val) == 'string') $hash = $val;
        }
        return $ret;
    }

    public function add(string $uid, string $pwd='')
    {
        $val = 1;
        if ($pwd) $val = password_hash($pwd, PASSWORD_BCRYPT);
        $this->uids[$uid] = $val;
        logger()->log(['add', $uid, $val]);
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
    private mixed $hash = NULL;
    private string $pwd = '';
    private bool $valid = false;

    public function set(string $uid)
    {
        $this->uid = $uid;
        $this->valid = reg()->retrieve($this->uid, $this->hash);
    }

    public function isEncrypted()
    {
        return !is_null($this->hash);
    }

    public function isValid() : bool
    {
        return $this->valid;
    }

    public function uid()
    {
        return $this->uid;
    }

    public function pwd()
    {
        return $this->pwd;
    }

    public function check()
    {
        if (!$this->valid) self::welcome();
        // logger()->log('check', $this->hash);
        if ($this->isEncrypted())
        {
            session_start();
            // logger()->log('check', $_SESSION);
            if (!(
                isset($_SESSION['uid']) &&
                isset($_SESSION['pwd']) &&
                $_SESSION['uid'] == $this->uid
            )) $this->login();
            $this->pwd = $_SESSION['pwd'];
        }
    }

    public function checkPwd(string $pwd)
    {
        return is_null($this->hash) ? true :
            hash_equals($this->hash, crypt($pwd, $this->hash));
    }


    public static function welcome()
    {
        fnc\clearSession();
        header('Location: welcome.php');
    }

    private function login()
    {
        fnc\clearSession();
        $this->go('login');
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
