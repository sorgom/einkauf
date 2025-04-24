<?php
require_once('DataObjects.php');

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
        $oStates = states();
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
