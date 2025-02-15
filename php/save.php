<?php
if (isset($_POST['skip'])) {
    header('Location: /');
    exit;
}
$file = "data/FF00E1A4.txt";
$data = trim($_POST['data']);
if (empty($data)) {
    header('Location: input.php');
    exit;
}
$fh = fopen($file, "w") or die("Unable to open $file");
fwrite($fh, $_POST['data']);
fclose($fh);
header('Location: /');
exit;
?>