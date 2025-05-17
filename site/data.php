<?php
require_once('usr.php');
require_once('fnc.php');
require_once('logger.php');


abstract class UsrData
{
    private string $file;
    public function __construct(string $ext)
    {
        $this->file = 'data/' . usr()->uid() . ".$ext";
    }
    public function _delete()
    {
        if (file_exists($this->file)) unlink($this->file);
    }
    protected function _save(string &$cont)
    {
        logger()->log('->', $this->file);
        fnc\save($this->file, $cont);
    }
    protected function _load(&$cont)
    {
        logger()->log('<-', $this->file);
        return fnc\load($cont, $this->file);
    }
    protected function data()
    {
        return file_exists($this->file);
    }
}

class States extends UsrData
{
    private array $states = [];
    public function __construct(bool $load=false)
    {
        parent::__construct('log');
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
        $js = json_encode($this->states);
        $this->_save($js);
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
        return isset($this->states[$id]) ? $this->states[$id] : '';
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

class Data extends UsrData
{
    private array $heads = [];
    private array $items = [];
    private string $notes = '';
    private string $ps = '?';

    public function __construct(bool $load=false)
    {
        parent::__construct('data');
        if ($load) $this->load();
    }

    public function load()
    {
        if ($this->_load($data))
        {
            if (usr()->isEncrypted())
            {
                require_once('crypter.php');
                crypter()->decode($data, usr()->pwd(), $data);
            }
            [$this->heads, $this->items, $this->notes] = json_decode($data, true);
        }
        else if (fnc\load($txt, 'template.txt'))
            $this->set($txt);
    }

    public function save()
    {
        $data = json_encode([$this->heads, $this->items, $this->notes]);
        if (usr()->isEncrypted())
        {
            require_once('crypter.php');
            crypter()->encode($data, usr()->pwd(), $data);
        }
        $this->_save($data);
    }

    function menuData(&$data)
    {
        $data = [];
        foreach($this->items as $cnr => $i)
        {
            if (!empty($i))
            {
                $data[] = [ $cnr, $this->heads[$cnr], states()->cl($cnr) ];
            }
        }
    }

    function ItemData(&$data, int $cnr)
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
            $data = [ $cnr, $this->heads[$cnr], states()->cl($cnr), $res];
        }
        else $data = [ $cnr, 'NN', '', []];
    }

    public function txt(&$data)
    {
        $res = [$this->notes, ''];
        foreach ($this->heads as $cnr => $head)
        {
            $res[] = "@ $head";
            $res[] = fnc\impl($this->items[$cnr]);
            $res[] = '';
        }
        $data = trim(fnc\impl($res)) . "\n";
    }

    public function heads()
    {
        return $this->heads;
    }

    public function items(int $cnr)
    {
        return $this->has($cnr) ? self::toItems($this->items[$cnr], 'fnc\isi') : [];
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

        fnc\clean($txt, $text);

        $rx = '/^@ *(.+)\n?/m';

        if (preg_match_all($rx, $txt, $m))
        {
            $post = [];
            $ttls = $m[1];
            $data = array_map('trim', preg_split($rx, $txt));
            $this->notes = array_shift($data);
            foreach ($data as $cnr => $txt)
            {
                $item = self::txt2lines($txt);
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

    private static function toItems($a)
    {
        return array_values(array_filter($a, 'fnc\isi'));
    }

    private static function txt2lines(string $txt)
    {
        $lines = fnc\expl($txt);
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

    //  re-assign states to new order
    private function restate()
    {
        if ($this->data())
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
