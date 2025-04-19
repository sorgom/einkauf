<?php
    require_once('usr.php');

    $uid = $_POST['uid'];
    if (isset($_POST['cancel']))
    {
        goView();
    }

    if (empty($_POST['txt'])) go('input.php');

    $txt = &$_POST['txt'];

    require_once('fio.php');
    clean($txt);

    //  make sure the lost and found "@ ?" section is at end
    if (preg_match('/^@ *\.\.\.$/ms', $txt))
    {
        // empty ones
        $txt = preg_replace('/(^|\n)@ *\.\.\.(?:\n(\s*@)|\s*$)/', '\1\2', $txt);

        $rx = '/(^|\n)@ *\.\.\.(?:\n(?:@|(.*?))?)(\n@|$)/s';
        if (preg_match_all($rx, $txt, $m))
        {
            $a = array_merge(... array_map('xpl', $m[2]));
            $txt = preg_replace($rx, '\1\3', $txt);
            addNfd($txt, $a);
            despace($txt);
        }
    }
    save($txt);
    goView();
?>
