<?php
//  common html output with server host name as title
?>
<!DOCTYPE html>
<html lang=de>
<head>
<title><?php echo explode('.', $_SERVER['SERVER_NAME'])[0] ?></title>
<meta charset='UTF-8'>
<link rel=stylesheet href=view.css>
<link rel=icon type='image/svg' href='img/check_icon.svg'>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head><body>
<script src=literals.js></script>
<script>
    lit.setSrv('<?php echo $_SERVER['SERVER_NAME']; ?>');
</script>
<script src=view.js></script>

<?php
    function jsView($class, $uid, &$data)
    {
        echo "<script>new $class('$uid', ";
        echo json_encode($data);
        echo ');</script></body></html>';
    }
?>
