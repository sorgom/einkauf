<!DOCTYPE html>
<html lang=de>
<head>
<title><?php echo explode('.', $_SERVER['SERVER_NAME'])[0] ?></title>
<meta charset='UTF-8'>
<link rel=stylesheet href='site.css'>
<link rel=icon type='image/gif' href='img/check_icon.svg'>
<style>
</style>
</head><body>
<?php
    function toClass($id)
    {
        global $states;
        if (!isset($states[$id])) return '';
        $v = $states[$id];
        return ' class=' . ($v == 1 ? 'x' : $v);
    }

    function b_top($ttl, $v)
    {
        global $uid;
        echo "<div id=top><a href=/?$uid" . toClass($v) . "><p>$ttl</p></a></div>\n";
    }

    function b_chap($cnr, $ttl)
    {
        global $uid;
        echo "<a href=/?$uid&$cnr" . toClass($cnr) ."><p>$ttl</p></a>\n";
    }

    function b_item($cnr, $inr, $ttl)
    {
        $id = "$cnr.$inr";
        echo "<div id=$id" . toClass($id) . "><a><p>$ttl</p></a></div>\n";
    }

    function b_base($id, $dest, $param=NULL)
    {
        global $uid;
        echo "<a id=$id href=$dest?$uid" . (is_null($param) ? '' : "&$param") . "> </a>\n";
    }

    function b_reset($cnr)
    {
        b_base('reset', 'reset.php', $cnr);
    }

    function b_edit()
    {
        b_base('edit', 'input.php');
    }

    function b_new_go()
    {
        b_base('register', 'register.php');
    }

    function b_reg_go()
    {
        b_base('start', '/');
    }

    function b_remove($cnr)
    {
        b_base('remove', 'remove.php', $cnr);
    }

    function b_remove_confirm($cnr, $ttl)
    {
        global $uid;
        echo "<a id=remove_confirm href=remove.php?$uid&$cnr&X><p>$ttl</p></a>\n";
    }

    function b_back()
    {
        echo "<a id=back href=javascript:history.back()> </a>\n";
    }

    function b_imprint()
    {
        echo "<a id=imprint href='imprint.php' title='Imprint / Impressum'> </a>\n";
    }

    function b_imprint_back()
    {
        echo "<a id=imprint_back href=javascript:history.back()> </a>\n";
    }
?>
