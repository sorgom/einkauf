<?php
function dtxt($name)
{
    $dir = 'txt';
    $file = "$dir/$name.txt";
    if (!file_exists($file)) $file = "$dir/$name.default.txt";
    return trim(file_get_contents($file));
}
?>
