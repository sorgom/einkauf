<?php

require_once('DataObjects.php');

abstract class StateElem
{
    private string $id;
    private string $cl = '';
    protected function __construct(string|int $id)
    {
        global $states;
        $this->id = $id;
        $this->cl = States::instance()->cl($id);
    }

    public function isDone()
    {
        return $this->cl == 'x';
    }
    public function isOut()
    {
        return $this->cl == 'y';
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
    protected static function out(... $items)
    {
        echo implode('', $items) . "\n";
    }
}

abstract class TextElem extends StateElem
{
    protected string $ttl;
    protected function __construct(string|int $id, string $ttl)
    {
        parent::__construct($id);
        $this->ttl = $ttl;
    }
}

class MenuEntry extends TextElem
{
    public function __construct(int $id, string $ttl)
    {
        parent::__construct($id, $ttl);
    }
    public function html()
    {
        self::out('<a href=/?', Usr::instance()->uid(), '&' , $this->id() , $this->clstr(), '><p>', htmlentities($this->ttl), '</p></a>');
    }
}

class Menu
{
    public function __construct()
    {
        $order = [array(), array(), array()];
        foreach (Data::instance()->heads() as $nr => $ttl)
        {
            if (empty(Data::instance()->items()[$nr])) continue;
            $e = new MenuEntry($nr, $ttl);
            $p = $e->isOut() ? 1 : ($e->isDone() ? 2 : 0);
            $order[$p][] = $e;
        }
        foreach (array_merge(... $order) as $e)
        {
            $e->html();
        }
    }
}

class Item extends TextElem
{
    public function __construct(int $cnr, int $inr, string $ttl)
    {
        parent::__construct("$cnr.$inr", $ttl);
    }
    public function html()
    {

        self::out('<div id=', $this->id(), $this->clstr(), '><a><p>', htmlentities($this->ttl), '</p></a></div>');
    }
}

class ItemList
{
    public function __construct($cnr)
    {
        $inr = 0;
        foreach (Data::instance()->items()[$cnr] as $i)
        {
            if (empty($i)) echo "<hr>\n";
            elseif (is_array($i));
            elseif ($i[0] == '#')
            {
                echo '<h3>' . substr($i, 1) . "</h3>\n";
            }
            else
            {
                new Item($cnr, $inr, $i)->html();
                ++$inr;
            }
        }
    }
}

?>
