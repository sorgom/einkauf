<?php
function dtxt($name)
{
    $file = "$name.txt";
    if (!file_exists($file)) $file = "$name.default.txt";
    return trim(file_get_contents($file));
}
?>
