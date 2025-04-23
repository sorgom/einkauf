<?php
namespace fn;

function isl(string $c)
{
    return !(empty($c) || $c[0] == '#');
}
function xpl(string $s) { return explode("\n", $s); }
function impl($a) { return implode("\n", $a); }

?>
