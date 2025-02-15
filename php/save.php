<?php
$user = "FF00E1A4";
if (isset($_POST['skip'])) {
    header('Location: /');
    exit;
}
$txt = "data/$user.txt";
$log = "data/$user.json";
$data = trim($_POST['data']);
if (empty($data)) {
    header('Location: input.php');
    exit;
}
file_put_contents($txt, $data);
unlink($log);
header('Location: /');
exit;
?>