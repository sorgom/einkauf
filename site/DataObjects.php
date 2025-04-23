<?php

require_once('UsrObjects.php');

abstract class DataObject
{
    private string $file;
    private static string $dir = 'data';
    public function __construct(string $ext)
    {
        global $uid;
        $this->file = self::$dir . '/' . Usr::instance()->uid() . ".$ext";
    }
    public function _delete()
    {
        if (file_exists($this->file)) unlink($this->file);
    }
    protected function _save(mixed $cont)
    {
        if (!is_dir(self::$dir)) mkdir(self::$dir);
        file_put_contents($this->file, $cont);
    }
    protected function _load(&$cont)
    {
        $ok = file_exists($this->file);
        if ($ok) $cont = file_get_contents($this->file);
        return $ok;
    }
    protected static function isl(string $c)
    {
        return !(empty($c) || $c[0] == '#');
    }

    protected static function xpl(string $s) { return explode("\n", $s); }

    protected static function impl($a) { return implode("\n", $a); }
}

class States extends DataObject
{
    private array $states = [];
    public function __construct(bool $load=false)
    {
        parent::__construct('log.json');
        if ($load) $this->load();
    }
    public function load()
    {
        if ($this->_load($data)) $this->states = json_decode($data, true);
        else $this->states = [];
    }
    public function save()
    {
        if (empty($this->states)) $this->_delete();
        else $this->_save(json_encode($this->states));
    }

    public function given()
    {
        return !(empty($this->states));
    }

    public function cl(... $ids)
    {
        $id = implode('.', $ids);
        $cl = '';
        if (isset($this->states[$id]))
        {
            // this is compatibility
            // former state was 1 for done
            // now it's:
            // x for done
            // y for postponed
            $v = $this->states[$id];
            $cl = $v == 1 ? 'x' : $v;
        }
        return $cl;
    }

    public function set($val, ...$ids)
    {
        if ($val) $this->states[implode('.', $ids)] = $val;
    }

    public function reset(int $cnr)
    {
        unset($this->states[$cnr]);
        $rx = "/^$cnr\.)/";
        foreach (array_keys($this->states) as $k)
        {
            if (preg_match($rx, $k)) unset($this->states[$k]);
        }
    }

    public static function instance()
    {
        static $instance = new States(true);
        return $instance;
    }
}

class Data extends DataObject
{
    private array $heads = [];
    private array $items = [];

    public function __construct(bool $load=false)
    {
        parent::__construct('data.json');
        if ($load) $this->load();
    }
    public function load()
    {
        if ($this->_load($data)) [$this->heads, $this->items] = json_decode($data, true);
    }
    public function save()
    {
        $this->_save(json_encode([$this->heads, $this->items]));
    }

    public function heads()
    {
        return $this->heads;
    }
    public function items()
    {
        return $this->items;
    }
    public function lines(int $cnr)
    {
        return array_values(array_filter($this->items[$cnr], 'DataObject::isl'));
    }

    public function given()
    {
        return !(empty($this->heads));
    }

    public function set(string &$txt)
    {
        $heads = array();
        $items = array();
        if (preg_match('/^.*?(@.+)/ms', $txt, $m))
        {
            $lines = explode("\n", $m[1]);
            $lSet = false;
            $lOk  = false;
            $item = NULL;
            foreach ($lines as $line)
            {
                if (empty($line))
                {
                    $lSet = true;
                }
                elseif ($line[0] == '@')
                {
                    if (is_array($item)) $items[] = $item;
                    $item = array();
                    $heads[] = trim(substr($line, 1));
                    $lOk = false;
                }
                elseif ($line[0] == '#')
                {
                    preg_match('/^#+ *(.*)/', $line, $m);
                    $item[] = "# $m[1]";
                    $lOk = false;
                }
                else
                {
                    if ($lOk && $lSet) $item[] = '';
                    $item[] = $line;
                    $lSet = false;
                    $lOk = true;
                }
            }
            if (is_array($item)) $items[] = $item;
        }
        $this->heads = $heads;
        $this->items = $items;
    }

    public function remove(int $cnr)
    {
        $res = [];
        $nfd = [];
        $lines = $this->lines($cnr);
        $states = States::instance();
        foreach ($lines as $inr => $i)
        {
            if ($states->cl($cnr, $inr) == 'y') $nfd[] = $line;
        }
        foreach ($this->heads as $cnr => $head)
        {

        }
    }

    public static function instance()
    {
        static $instance = new Data(true);
        return $instance;
    }
}

?>
