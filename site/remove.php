<?php
    session_start();
    require_once('usr.php');
    setUid();
    checkUid();
    require_once('fio.php');

    $cnr = getParam();
    rData($heads, $_);

    if ($cnr < 1 || $cnr > count($heads)) goView();
    $pos = $cnr - 1;

    if (count($params) < 2)
    {
        require_once('body.php');
        b_remove_confirm($cnr, $heads[$pos]);
        b_back();
        ?></body></html><?php
    }
    else
    {
        rTxt($txt);
        $rx = '/^@.*/m';
        preg_match_all($rx, $txt, $heads);
        $heads = array_shift($heads);
        $items = preg_split($rx, $txt);

        $res = array(array_shift($items));
        unset($items[$pos]);
        unset($heads[$pos]);

        foreach ($heads as $pos => $head)
        {
            $res[] = $head;
            $res[] = $items[$pos];
        }

        $txt = implode('', $res);

        txt2data($txt, $heads, $items);
        wData($heads, $items);
        wTxt($txt);

        rDone($done);
        $res = array();
        foreach (array_keys($done) as $key)
        {
            $item = explode('.', $key);
            $cn = $item[0];
            if ($cn < $cnr) $res[$key] = 1;
            elseif ($cn > $cnr)
            {
                --$item[0];
                $res[implode('.', $item)] = 1;
            }
        }
        wDone($res);
        goView();
    }
?>
