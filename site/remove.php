<?php
    require_once('usr.php');
    setUid();
    $cnr = getParam();
    require_once('data.php');

    if (count($params) < 2)
    {
        require_once('body.php');
        rData($heads, $_);
        b_remove_confirm($cnr, $heads[$cnr]);
        b_back();
        ?></body></html><?php
    }
    else
    {
        rTxt($txt);
        rStates($states);
        $rx = '/^@.*\n?/m';
        preg_match_all($rx, $txt, $heads);
        $heads = array_shift($heads);
        $items = preg_split($rx, $txt);

        $res = array(array_shift($items));

        foreach ($heads as $p => $head)
        {
            if ($p != $cnr)
            {
                $res[] = $head;
                $res[] = $items[$p];
            }
        }
        $txt = implode('', $res);

        $nfd = array();

        $rx = '/^@ \.\.\.\n(.*)/ms';
        if (preg_match($rx, $txt, $m))
        {
            $nfd = xpl($m[1]);
            $txt = preg_replace($rx, '', $txt);
        }

        $a = array_values(array_filter(xpl($items[$cnr]), 'isl'));
        foreach ($a as $p => $line)
        {
            $c = "$cnr.$p";
            if (isset($states[$c]) && $states[$c] == 'y') $nfd[] = $line;
        }
        addNfd($txt, $nfd);
        save($txt, preg_match('/^@ \.\.\.\n?$/', $heads[$cnr]));
        goView();
    }
?>
