<?php
//  user data
require_once('usr.php');
require_once('fnc.php');
require_once('tracer.php');

//  common user data file handling class
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
        fnc\save($this->file, $cont);
    }
    protected function _load(&$cont)
    {
        return fnc\load($cont, $this->file);
    }
    protected function data()
    {
        return file_exists($this->file);
    }
}

//  user item and todo list states tracker class
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

    //  retrieve current state class of todo list or item
    public function cl(... $ids)
    {
        $id = implode('.', $ids);
        return isset($this->states[$id]) ? $this->states[$id] : '';
    }

    //  set current state class of todo list or item
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

    //  reset a complete todo list
    public function reset(int $lnr)
    {
        unset($this->states[$lnr]);
        $rx = "/^$lnr\.\d+$/";
        foreach (array_keys($this->states) as $k)
        {
            if (preg_match($rx, $k)) unset($this->states[$k]);
        }
    }
}

//  user data class
class Data extends UsrData
{
    private array $heads = [];
    private array $items = [];
    private string $notes = '';
    private string $ps = '?';

    public function __construct(bool $load=false)
    {
        parent::__construct('data');
        require_once('crypter.php');
        if ($load) $this->load();
    }

    //  load user data
    public function load()
    {
        //  if data read
        if ($this->_load($data))
        {
            crypter()->decode($data, usr()->key(), $data);
            [$this->heads, $this->items, $this->notes] = json_decode($data, true);
        }
        //  otherwise start with nothing
        else
            [$this->heads, $this->items, $this->notes] = [[], [], ''];
        // //  otherwise start with template
        // else if (fnc\load($txt, 'template.txt'))
        //     $this->set($txt);
    }

    //  save user data
    public function save()
    {
        $data = json_encode([$this->heads, $this->items, $this->notes]);
        crypter()->encode($data, usr()->key(), $data);
        $this->_save($data);
    }

    //  user data overview
    //  lists all non empty todo lists
    function overview(mixed &$entries)
    {
        $entries = [];
        foreach($this->items as $lnr => $i)
        {
            if (!empty($i))
            {
                $entries[] = [ $lnr, $this->heads[$lnr], states()->cl($lnr) ];
            }
        }
    }

    //  user todo list
    function todoList(&$data, int $lnr)
    {
        if ($this->has($lnr))
        {
            $res = [];
            $inr = 0;
            foreach ($this->items[$lnr] as $i)
            {
                if (empty($i)) $e = '';
                else if ($i[0] == '#') $e = substr($i, 2);
                else {
                    $e = [$i, states()->cl($lnr, $inr)];
                    ++$inr;
                }
                $res[] = $e;
            }
            $data = [ $lnr, $this->heads[$lnr], states()->cl($lnr), $res];
        }
        else $data = [ $lnr, 'NN', '', []];
    }

    //  retrieve text for edit form
    public function txt(&$data)
    {
        $res = [$this->notes, ''];
        foreach ($this->heads as $lnr => $head)
        {
            //  skip "postponed" list if empty
            if ($head == $this->ps && empty($this->items[$lnr])) continue;
            $res[] = "@ $head";
            $res[] = fnc\impl($this->items[$lnr]);
            $res[] = '';
        }
        $data = trim(fnc\impl($res)) . "\n";
    }

    public function heads()
    {
        return $this->heads;
    }

    public function items(int $lnr)
    {
        return $this->has($lnr) ? self::toItems($this->items[$lnr], 'fnc\isi') : [];
    }

    public function given()
    {
        return !(empty($this->heads));
    }

    //  set user data form editor text (or template)
    public function set(string &$text)
    {
        $this->heads = [$this->ps];
        $this->items = [[]];
        $this->notes = '';

        fnc\clean($txt, $text);

        // remove orphaned @s
        $txt = preg_replace('/^@(?:\n+|$)/m', '', $txt);

        $rx = '/^@ *(.+)\n?/m';

        if (preg_match_all($rx, $txt, $m))
        {
            $post = [];
            $ttls = $m[1];
            $data = array_map('trim', preg_split($rx, $txt));
            $this->notes = array_shift($data);
            foreach ($data as $lnr => $txt)
            {
                $item = self::txt2list($txt);
                $ttl  = $ttls[$lnr];
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
                natcasesort($this->items[0]);
        }
        else $this->notes = $txt;

        $this->restate();
        $this->save();
    }

    //  remove a todo list
    //  and transfer postponed items
    public function remove(int $lnr)
    {
        if ($this->has($lnr))
        {
            $post = [];
            $states = states();
            $items = $this->items($lnr);
            foreach ($items as $inr => $i)
            {
                if ($states->cl($lnr, $inr) == 'y') $post[] = $i;
            }
            $this->items[$lnr] = [];
            states()->reset($lnr);
            if (!empty($post))
            {
                $this->items[0] = array_unique(array_merge($post, $this->items[0]));
                natcasesort($this->items[0]);
                states()->reset(0);
            }
            states()->save();
            $this->save();
        }
    }

    //  transfer postponed items of a todo list
    public function postpone(int $lnr)
    {
        if ($lnr > 0 && $this->has($lnr))
        {
            $post = [];
            $done = [];
            $states = states();
            $items0 = array_values($this->items[0]);
            foreach ($this->items($lnr) as $inr => $i)
            {
                $cl = $states->cl($lnr, $inr);
                if ($cl == 'y') $post[] = $i;
                else $done[] = $i;
            }
            if (!empty($post))
            {
                $items0 = array_unique(array_merge($post, $items0));
                natcasesort($items0);
            }

            foreach ($done as $i)
            {
                $index = array_search($i, $items0);
                if ($index !== false) unset($items0[$index]);
            }

            if (array_values($items0) != array_values($this->items[0]))
            {
                $map = [];
                foreach ($this->items(0) as $inr => $i)
                {
                    $map[$i] = $states->cl(0, $inr);
                }
                $this->items[0] = array_values($items0);
                $states->reset(0);
                foreach ($this->items(0) as $inr => $i)
                {
                    if (isset($map[$i])) $states->set($map[$i], 0, $inr);
                }
                $states->save();
                $this->save();
            }
        }
    }

    //  filter items only
    private static function toItems($a)
    {
        return array_values(array_filter($a, 'fnc\isi'));
    }

    //  todo list wise lines parser
    private static function txt2list(string $txt)
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

    //  safety handler: todo list within data range
    private function has(int $lnr)
    {
        return ($lnr >= 0) && (count($this->heads) > $lnr);
    }

    //  re-assign states to new order
    //  by string mapping
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
                foreach ($oData->heads() as $lnr => $head)
                {
                    if ($lnr == 0) continue;
                    foreach ($oData->items($lnr) as $inr => $item)
                    {
                        $map->set($oStates->cl($lnr, $inr), $head, $item);
                    }
                }
                foreach ($this->heads as $lnr => $head)
                {
                    if ($lnr == 0) continue;
                    $items = $this->items($lnr);
                    if (!empty($items))
                    {
                        $all = true;
                        $cst = 'x';
                        foreach ($items as $inr => $item)
                        {
                            $c = $map->cl($head, $item);
                            if ($all && $c) $cst = $c == 'y' ? 'y' : $cst;
                            else $all = false;
                            $nStates->set($map->cl($head, $item), $lnr, $inr);
                        }
                        if ($all) $nStates->set($cst, $lnr);
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
