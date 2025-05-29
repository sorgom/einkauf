
<?php
//  user main view
require_once('usr.php');
usr()->check();
require_once('view.php');
require_once('data.php');
$x = usr()->param();
$data = NULL;
switch (true)
{
    //  parameter 'e': user text input
    case $x === 'e':
        data()->txt($data);
        $class = 'InputForm';
        break;
    //  NULL or non integer
    case is_null($x) || !ctype_digit($x):
        data()->menuData($data);
        $class = 'Menu';
        break;
    //  otherwise: chapter number, display items of chapter
    default:
        data()->ItemData($data, $x);
        $class = 'Items';
}
jsView($class, usr()->uid(), $data);
?>
