<!DOCTYPE html>
<html lang=de>
<head>
<title><?php echo explode('.', $_SERVER['SERVER_NAME'])[0] ?></title>
<meta charset='UTF-8'>
<link rel=stylesheet href='view.css'>
<link rel=icon type='image/gif' href='img/check_icon.svg'>
<style>
</style>
</head><body>
<?php
    require_once('data.php');

    abstract class BaseElem
    {
        private string $id;

        protected function __construct(string|int $id)
        {
            $this->id = $id;
        }

        protected static function cat(... $items) : string
        {
            return implode('', $items);
        }

        protected static function out(... $items)
        {
            echo implode('', $items) . "\n";
        }

        protected function id()
        {
            return $this->id;
        }
        protected function idstr()
        {
            return " id=$this->id";
        }
        protected static function anc(mixed $param=NULL, string $target='')
        {
            return self::cat('<a href=', (empty($target) ? '/' : "$target.php"), '?', usr()->uid(), (is_null($param) ? '' : "&$param") );
        }
    }

    abstract class StateElem extends BaseElem
    {
        private string $cl = '';
        protected function __construct(string|int $id)
        {
            parent::__construct($id);
            $this->cl = states()->cl($id);
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
            self::out(self::anc($this->id()),  $this->clstr(), '><p>', htmlentities($this->ttl), '</p></a>');
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
            new MenuEdit();
            echo "<div id=menu>\n";
            foreach (array_merge(... $order) as $e)
            {
                $e->html();
            }
            echo "</div>\n";
            new MenuImprint();
        }
    }

    class Item extends TextElem
    {
        public function __construct(int $cnr, int $inr, string $ttl)
        {
            parent::__construct("$cnr.$inr", $ttl);
            $this->html();
        }
        public function html()
        {

            self::out('<div', $this->idstr(), $this->clstr(), '><a><p>', htmlentities($this->ttl), '</p></a></div>');
        }
    }

    class ItemList
    {
        public function __construct($cnr)
        {
            new ChapTop($cnr);
            echo "<div id=items>\n";
            $inr = 0;
            foreach (Data::instance()->items()[$cnr] as $i)
            {
                if (empty($i)) echo "<hr>\n";
                elseif (is_array($i));
                elseif ($i[0] == '#')
                {
                    echo '<h3>' . substr($i, 2) . "</h3>\n";
                }
                else
                {
                    new Item($cnr, $inr, $i);
                    ++$inr;
                }
            }
            echo "</div>\n<div id=bottom>\n";
            new ChapReset($cnr);
            new ChapRemove($cnr);
            echo "</div>\n";
        }
    }

    class ChapTop extends TextElem
    {
        public function __construct(int $cnr)
        {
            parent::__construct($cnr, Data::instance()->heads()[$cnr]);
            $this->html();
        }
        public function html()
        {
            self::out('<div id=top>', self::anc(), $this->idstr(), $this->clstr(), '><p>', htmlentities($this->ttl), '</p></a></div>');
        }
    }

    class Link extends BaseElem
    {
        private string $target;
        private mixed $param;
        private string $ttl;
        public function __construct(string $id, mixed $param=NULL, string $target='', $ttl = '')
        {
            parent::__construct($id);
            $this->target = $target;
            $this->param = $param;
            $this->ttl = $ttl;
            $this->html();
        }
        public function html()
        {
            self::out(self::anc($this->param, $this->target), $this->idstr(), '>', $this->ttl, '</a>');
        }
    }

    class ChapReset extends Link
    {
        public function __construct($cnr) { parent::__construct('reset', $cnr, 'reset'); }
    }
    class ChapRemove extends Link
    {
        public function __construct($cnr) { parent::__construct('remove', $cnr, 'remove'); }
    }
    class RemoveConfirm extends Link
    {
        public function __construct($cnr)
        {
            parent::__construct('remove_confirm', "$cnr&_", 'remove', '<p>' . data()->heads()[$cnr] . '</p>');
        }
    }

    class MenuEdit extends Link
    {
        public function __construct() { parent::__construct('edit', NULL, 'input'); }
    }
    class MenuImprint extends Link
    {
        public function __construct() { parent::__construct('imprint', NULL, 'imprint'); }
    }
    class UsrStart extends Link
    {
        public function __construct() { parent::__construct('start'); }
    }

    class BackElem extends BaseElem
    {
        public function __construct(string $id)
        {
            parent::__construct($id);
            $this->html();
        }
        private function html()
        {
            self::out('<a' , $this->idstr(), ' href=javascript:history.back()> </a>');
        }
    }
    class Back extends BackElem
    {
        public function __construct() { parent::__construct('back'); }
    }
    class ImprintBack extends BackElem
    {
        public function __construct() { parent::__construct('imprint_back'); }
    }

    class UsrNew
    {
        public function __construct() { $this->html(); }
        private function html()
        {
            echo "<a href=start.php id=register> </a>\n";
        }
    }
?>
