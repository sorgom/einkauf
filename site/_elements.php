<?php

require_once('_states.php');

abstract class StateElem
{
    private string $id;
    private string $cl = '';
    protected function __construct(mixed $id)
    {
        global $states;
        $this->id = $id;
        $this->cl = $states->cl($id);
    }

    public function cl()
    {
        return $this->cl;
    }
    protected function clstr()
    {
        return $this->cl ?  " class=$this->cl" : '';
    }
    protected function id()
    {
        return $this->id;
    }
    public function isDone()
    {
        return $this->cl == 'x';
    }
    public function isOut()
    {
        return $this->cl == 'y';
    }
}

abstract class TextElem extends StateElem
{
    protected string $ttl;
    protected function __construct(mixed $id, string $ttl)
    {
        parent::__construct($id);
        $this->ttl = $ttl;
    }
}

class MenuEntry extends TextElem
{
    public function __construct(mixed $id, string $ttl)
    {
        parent::__construct($id, $ttl);
    }
    public function say()
    {
        global $uid;
        echo "<a href=/?$uid&" . $this->id() . $this->clstr() . "><p>$this->ttl</p></a>\n";
    }
}

class Item extends TextElem
{
    public function __construct(mixed $id, string $ttl)
    {
        parent::__construct($id, $ttl);
    }
    public function say()
    {
        echo '<div id=' . $this->id() . $this->clstr() . "><a><p>$this->ttl</p></a></div>\n";
    }
}

class Menu
{
    private array $order = [array(), array(), array()];
    public function add(int $id, string $ttl)
    {
        $e = new MenuEntry($id, $ttl);
        $p = $e->isOut() ? 1 : ($e->isDone() ? 2 : 0);
        $this->order[$p][] = $e;
    }
    public function say()
    {
        foreach (array_merge(... $this->order) as $e)
        {
            $e->say();
        }
    }
}
?>
