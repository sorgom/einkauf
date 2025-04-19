<?php
    $uid = NULL;
    $params = array();

    function regFile()
    {
        return 'data/reg.json';
    }
    function getReg()
    {
        $rf = regFile();
        if (file_exists($rf))
            return json_decode(file_get_contents($rf), true);
        return array();
    }

    function isUid($x)
    {
        $reg = getReg();
        return isset($reg[$x]);
    }

    function setUid()
    {
        global $uid, $params;
        if ($_GET)
        {
            $params = array_keys($_GET);
            $uid = array_shift($params);
            if (!isUid($uid)) goNew();
        }
        else goNew();
    }

    function getParam()
    {
        global $params;
        return empty($params) ? NULL : $params[0];
    }

    function go($dest, $param=NULL)
    {
        global $uid;
        header("Location: $dest?$uid" . (is_null($param) ? '' : "&$param"));
        exit;
    }

    function goView($param=NULL)
    {
        global $uid;
        go('/', $param);
    }

    function goNew()
    {
        header('Location: new.php');
        exit;
    }
?>
