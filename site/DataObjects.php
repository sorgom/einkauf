<?php

require_once('UsrObjects.php');

abstract class DataObject
{
    private string $file;
    private static string $dir = 'data';
    public function __construct(string $ext)
    {
        global $uid;
        $this->file = self::$dir . '/' . usr()->uid() . ".$ext";
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

    protected static function isl(string $c) { return !(empty($c) || $c[0] == '#'); }

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
        $id = implode('.', $ids);
        if ($val) $this->states[$id] = $val;
        else {
            unset($this->states[$id]);
            if (preg_match('/^(\d+)\./', $id, $m))
            {
                unset($this->states[$m[1]]);
            }
        }
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
    private string $notes = '';

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
        $this->heads = [];
        $this->items = [];
        $this->notes = '';

        $this->txt = trim(preg_replace('/^ *| *$/m', '', preg_replace('/\r\n|\r/', "\n", str_replace("\t", ' ', $txt))));

        if (preg_match('/^(.*?\n)?(@.+)/s', $txt, $m))
        {
            $this->notes = trim($m[1]);
            $lines = explode("\n", $m[2]);
            $lSet = false;
            $lOk  = false;
            $item = NULL;
            $nfd = [];
            $dm = true;
            foreach ($lines as $line)
            {
                if (empty($line))
                {
                    if ($dm) $lSet = true;
                }
                elseif ($line[0] == '@')
                {
                    $head = trim(substr($line, 1));
                    $dm = $head != '...';
                    if ($dm)
                    {
                        if (is_array($item)) $this->items[] = $item;
                        $item = [];
                        if (empty($head)) $head = '??';
                        $this->heads[] = $head;
                        $lOk = false;
                    }
                }
                elseif ($line[0] == '#')
                {
                    if ($dm)
                    {
                        preg_match('/^#+ *(.*)/', $line, $m);
                        $item[] = "# $m[1]";
                        $lOk = false;
                    }
                }
                else
                {
                    if ($dm)
                    {
                        if ($lOk && $lSet) $item[] = '';
                        $item[] = $line;
                        $lSet = false;
                        $lOk = true;
                    }
                    else $nfd[] = $line;
                }
            }
            if (is_array($item)) $this->items[] = $item;
            if (!empty($nfd))
            {
                $this->heads[] = '...';
                $this->items[] = $nfd;
            }
        }
        else $this->notes = $txt;
    }

    public function remove(int $cnr)
    {
        $res = [];
        $nfd = [];
        $lines = $this->lines($cnr);
        $states = states();
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

function states()
{
    return States::instance();
}
function data()
{
    return Data::instance();
}

?>
