<?php
require_once('usr.php');
if (usr()->valid())
{
    require_once('data.php');
    switch (usr()->param())
    {
    case 'txt':
        echo data()->txt();
        break;
    case 'menu':
        echo json_encode(data()->menuData());
        break;
    case 'items':
        echo json_encode(data()->ItemData(usr()->param(1)));
        break;
    }
}
?>
