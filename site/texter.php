<?php
require_once('fnc.php');

class Texter
{
    private string $file;
    private string $folder;
    private array $data = [];
    private string $code = '';

    public function read()
    {
        $this->data = ['' => []];
        foreach (glob($this->folder . '/*.txt') as $file) $this->add($file);
        $this->save();
    }
    private function add($file)
    {
        $text = file_get_contents($file);
        $rx = '/^===+ *(.*)/m';
        if (fnc\split($rx, $text, $keys, $data, $code))
        {
            foreach ($keys as $n => $key)
            $this->data[$code][$key] = $data[$n];
        }
    }

    public function set(string $code)
    {
        $this->code = $code;
    }

    private function load()
    {
        if (fnc\load($data, $this->file)) $this->data = json_decode($data, true);
        else $this->read();
    }

    private function save()
    {
        $js = json_encode($this->data);
        fnc\save($this->file, $js);
    }

    function get(&$ret, $key)
    {
        $ret = '';
        return ($this->code && $this->_get($ret, $key, $this->code)) || $this->_get($ret, $key, '');
    }

    function _get(&$ret, $key, $code)
    {
        $ok = false;
        if (isset($this->data[$code]) && isset($this->data[$code][$key]))
        {
            $ret = $this->data[$code][$key];
            $ok = true;
        }
        return $ok;
    }

    public function __construct($name = 'texter')
    {
        $this->file = "data/$name.json";
        $this->folder = $name;
        $this->load();
    }
}

function texter()
{
    static $instance = new Texter();
    return $instance;
}
function help()
{
    static $instance = new Texter('help');
    return $instance;
}

?>
