
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
        data()->overview($data);
        $class = 'Overview';
        break;
    //  otherwise: todo list number, display items of todo list
    default:
        data()->todoList($data, $x);
        $class = 'TodoList';
}
jsView($class, usr()->uid(), $data);
?>
