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
        // echo "File: $this->file\n";
        $ok = file_exists($this->file);
        if ($ok)
        {
            $cont = file_get_contents($this->file);
            // var_dump($cont);
        }
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
    private array $states = array();
    public function __construct(bool $load=false)
    {
        parent::__construct('log.json');
        if ($load) $this->load();
    }
    public function load()
    {
        if ($this->_load($data))
        {
            // var_dump($data);
            $this->states = json_decode($data, true);
        }
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
                    $item[] = "#$m[1]";
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

    public static function instance()
    {
        static $instance = new Data(true);
        return $instance;
    }
}

class Txt extends DataObject
{
    private string $txt = '';
    public function __construct(bool $load=false)
    {
        parent::__construct('txt');
        if ($load) $this->load();
    }
    public function load()
    {
        $this->_load($this->txt);
    }

    public function set(string $txt)
    {
        $this->txt = $txt;
        $this->clean();
        $this->locateNfd();
    }
    public function txt()
    {
        return $this->txt;
    }

    public function save()
    {
        $nData   = new Data();
        $nData->set($this->txt);
        $nStates = new States();

        $oData   = Data::Instance();
        $oStates = States::instance();
        if ($oStates->given() && $oData->given())
        {
            $map = new States();
            foreach ($oData->heads() as $cnr => $head)
            {
                foreach ($oData->lines($cnr) as $inr => $line)
                {
                    $map->set($oStates->cl($cnr, $inr), $head, $line);
                }
            }
            foreach ($nData->heads() as $cnr => $head)
            {
                foreach ($nData->lines($cnr) as $inr => $line)
                {
                    $nStates->set($map->cl($head, $line), $cnr, $inr);
                }
            }
        }
        $this->_save($this->txt);
        $nData->save();
        $nStates->save();
    }

    public static function instance()
    {
        static $instance = new Txt(true);
        return $instance;
    }

    private function despace()
    {
        $this->txt = preg_replace('/\n{3,}/', "\n\n", $this->txt);
    }

    private function clean()
    {
        $this->txt = trim(preg_replace('/^ *| *$/m', '', preg_replace('/\r\n|\r/', "\n", str_replace("\t", ' ', $this->txt))));
        $this->despace();
    }

    private function locateNfd()
    {
        if (preg_match('/^@ *\.\.\.$/ms', $this->txt))
        {
            // empty ones
            $this->txt = preg_replace('/(^|\n)@ *\.\.\.(?:\n(\s*@)|\s*$)/', '\1\2', $this->txt);

            $rx = '/(^|\n)@ *\.\.\.(?:\n(?:@|(.*?))?)(\n@|$)/s';
            if (preg_match_all($rx, $this->txt, $m))
            {
                $a = array_merge(... array_map('DataObject::xpl', $m[2]));
                $this->txt = preg_replace($rx, '\1\3', $this->txt);
                $this->addNfd($a);
                $this->despace();
            }
        }
    }

    private function addNfd($nfd)
    {
        if (!empty($nfd))
            $this->txt = trim($this->txt) . "\n\n@ ...\n" . self::impl(array_filter(array_unique($nfd), 'DataObject::isl'));
    }

}
?>
