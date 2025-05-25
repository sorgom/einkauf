
<?php
//  user main view
require_once('usr.php');
usr()->check();
require_once('view.php');
require_once('data.php');
$x = usr()->param();
$data = NULL;
switch ($x)
{
    //  no parameters: user chapters menu
    case NULL:
        data()->menuData($data);
        $class = 'Menu';
        break;
    //  parameter 'e': user text input
    case 'e':
        data()->txt($data);
        $class = 'InputForm';
        break;
    //  other parameter: chapter number, display items of chapter
    default:
        data()->ItemData($data, $x);
        $class = 'Items';
}
jsView($class, usr()->uid(), $data);
?>
