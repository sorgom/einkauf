<!DOCTYPE html>
<html lang=en>
<head>
<title>einkauf</title>
<meta charset="UTF-8">
<link rel=stylesheet href="site.css">
<script src=site.js></script>
</head>
<body>
<H3><a href=input.php>Eingabe</a></H3>
<div class=handy>
<?php
  $user = "FF00E1A4";
  $txt = "data/$user.txt";
  $log = "data/$user.json";
  if (!file_exists($txt)) {
      header('Location: input.php');
      exit;
  }
  $cont = file_get_contents($txt);
  $checks = array();
  if (file_exists($log)) {
      $checks = json_decode(file_get_contents($log), true);
  }

 
  $lines = explode("\n", $cont);
  $id = 0;
  $hr = false;
  $listing = false;
  foreach ($lines as $line) {
      $line = trim($line);
      $isshop = preg_match("/^# (.*)/", $line, $match);
      if ($isshop) {
        echo "<h2>$match[1]</h2>\n";
        $listing = true;
        $hr = true;
      } 
      else if ($listing)
      {
          if (empty($line) && !$hr) {
              echo "<hr/>\n";
              $hr = true;
          }
          else
          {
              ++$id;
              $checked = isset($checks[$id]) ? " checked" : "";
              echo "<p><input type=checkbox id=$id onclick='checkme(this)' $checked> <label for=$id>$line</label></p>\n";
              $hr = false;
          }
      }
  }
?>
</div>
</body></html>
