<?php

require_once('usr.php');

class Fnc
{
    protected static function isl(string $c) { return !(empty($c) || $c[0] == '#'); }
    protected static function impl($a) { return implode("\n", $a); }
}

abstract class DataObject extends Fnc
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
        if ($this->_load($data))
        {
            // bugfix: sometimes strange }"} at end
            // when run on server
            $data = preg_replace('/\}.*/', '}', $data);
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

    public function items(int $cnr)
    {
        $rx = "/^$cnr(?:\.\d+)?$/";
        $res = ['X' => 1];
        foreach($this->states as $k => $v)
        {
            if (preg_match($rx, $k)) $res[$k] = $v;
        }
        return $res;
    }
    public function chapters()
    {
        $res = [];
        foreach($this->states as $k => $v)
        {
            if (preg_match('/^\d+$/', $k)) $res[$k] = $v;
        }
        return $res;
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
        $rx = "/^$cnr\.\d+$/";
        foreach (array_keys($this->states) as $k)
        {
            if (preg_match($rx, $k)) unset($this->states[$k]);
        }
    }
}

class Data extends DataObject
{
    private array $heads = [];
    private array $items = [];
    private string $notes = '';
    private string $ps = '?';

    public function __construct(bool $load=false)
    {
        parent::__construct('data.json');
        if ($load) $this->load();
    }

    public function load()
    {
        $this->heads = [];
        $this->items = [];
        $this->notes = '';
        if ($this->_load($data))
        {
            // compatibility
            // was: heads, items
            // now: heads, items, notes
            [$this->heads, $this->items, $this->notes] = [... json_decode($data, true), ''];
        }
    }

    public function save()
    {
        $this->_save(json_encode([$this->heads, $this->items, $this->notes]));
    }

    public function heads()
    {
        return $this->heads;
    }

    public function lines(int $cnr)
    {
        return $this->has($cnr) ? $this->items[$cnr] : [];
    }

    public function items(int $cnr)
    {
        return $this->has($cnr) ? self::toItems($this->items[$cnr], 'Fnc::isl') : [];
    }

    function menuData()
    {
        $res = [];
        foreach($this->items as $cnr => $i)
        {
            if (!empty($i))
            {
                $res[] = [ $cnr, $this->heads[$cnr], states()->cl($cnr) ];
            }
        }
        return [ usr()->uid(), $res ];
    }
    function ItemData(int $cnr)
    {
        if ($this->has($cnr))
        {
            $res = [];
            $inr = 0;
            foreach ($this->items[$cnr] as $i)
            {
                if (empty($i)) $e = '';
                else if ($i[0] == '#') $e = substr($i, 2);
                else {
                    $e = [$i, states()->cl($cnr, $inr)];
                    ++$inr;
                }
                $res[] = $e;
            }
            return [ usr()->uid(), $cnr, $this->heads[$cnr], states()->cl($cnr), $res];
        }
        else return [ usr()->uid(), $cnr, 'NN', '', []];
    }

    public function given()
    {
        return !(empty($this->heads));
    }

    public function set(string &$text)
    {
        $this->heads = [$this->ps];
        $this->items = [[]];
        $this->notes = '';

        $txt = self::clean($text);

        $rx = '/^@ *(.+)\n?/m';

        if (preg_match_all($rx, $txt, $m))
        {
            $post = [];
            $ttls = $m[1];
            $data = array_map('trim', preg_split($rx, $txt));
            $this->notes = array_shift($data);
            foreach ($data as $cnr => $txt)
            {
                $item = self::txt2item($txt);
                $ttl  = $ttls[$cnr];
                if ($ttl == $this->ps)
                {
                    $post[] = $item;
                }
                else
                {
                    $this->heads[] = $ttl;
                    $this->items[] = $item;
                }
            }
            if (!empty($post))
                $this->items[0] = self::toItems(array_unique(array_merge(... $post)));
        }
        else $this->notes = $txt;

        $this->restate();
        $this->save();
    }

    public function remove(int $cnr)
    {
        if ($this->has($cnr))
        {
            $post = [];
            $states = states();
            $items = $this->items($cnr);
            foreach ($items as $inr => $i)
            {
                if ($states->cl($cnr, $inr) == 'y') $post[] = $i;
            }
            $this->items[$cnr] = [];
            if (!empty($post))
                $this->items[0] = array_unique(array_merge($post, $this->items[0]));

            states()->reset($cnr);
            if ($cnr != 0) states()->reset(0);
            states()->save();
            $this->save();
        }
    }

    public function txt()
    {
        $res = [$this->notes, ''];
        foreach ($this->heads as $cnr => $head)
        {
            $res[] = "@ $head";
            $res[] = Fnc::impl($this->items[$cnr]);
            $res[] = '';
        }
        return trim(Fnc::impl($res)) . "\n";
    }

    private static function toItems($a)
    {
        return array_values(array_filter($a, 'Fnc::isl'));
    }

    private static function txt2item(string $txt)
    {
        $lines = explode("\n", $txt);
        $lSet = false;
        $lOk  = false;
        $item = [];
        foreach ($lines as $line)
        {
            if (empty($line))
            {
                $lSet = true;
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
        return $item;
    }

    private function has(int $cnr)
    {
        return ($cnr >= 0) && (count($this->heads) > $cnr);
    }

    private static function clean(string &$text)
    {
        return trim(preg_replace('/^ *| *$/m', '', preg_replace('/\r\n|\r/', "\n", str_replace("\t", ' ', $text))));
    }

    //  re-assign states to new order
    private function restate()
    {
        $oData   = new Data(true);
        $oStates = states();
        $nStates = new States();
        if ($oStates->given() && $oData->given())
        {
            $map = new States();
            foreach ($oData->heads() as $cnr => $head)
            {
                if ($cnr == 0) continue;
                foreach ($oData->items($cnr) as $inr => $line)
                {
                    $map->set($oStates->cl($cnr, $inr), $head, $line);
                }
            }
            foreach ($this->heads as $cnr => $head)
            {
                if ($cnr == 0) continue;
                $items = $this->items($cnr);
                if (!empty($items))
                {
                    $all = true;
                    $cst = 'x';
                    foreach ($items as $inr => $line)
                    {
                        $c = $map->cl($head, $line);
                        if ($all && $c) $cst = $c == 'y' ? 'y' : $cst;
                        else $all = false;
                        $nStates->set($map->cl($head, $line), $cnr, $inr);
                    }
                    if ($all) $nStates->set($cst, $cnr);
                }
            }
        }
        $nStates->save();
    }
}

//  instances
function states()
{
    static $instance = new States(true);
    return $instance;
}
function data()
{
    static $instance = new Data(true);
    return $instance;
}

?>
