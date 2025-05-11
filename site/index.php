
<?php
require_once('usr.php');
usr()->check();
require_once('body.php');
require_once('data.php');
$x = usr()->param();
$data = NULL;
switch ($x)
{
    case NULL:
        data()->menuData($data);
        $class = 'Menu';
        break;
    case 'e':
        data()->txt($data);
        $class = 'InputForm';
        break;
    default:
        data()->ItemData($data, $x);
        $class = 'Items';
}
jsNew($class, usr()->uid(), $data);
?>
</body></html>
