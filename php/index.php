<!DOCTYPE html>
<html lang=en>
<head>
<title>einkauf</title>
<meta charset="UTF-8">
<link rel=stylesheet href="site.css">
<script src="site.js"></script>
</head>
<body>
<H3><a href=input.php>Eingabe</a></H3>
<div class=handy>
<?php
  $user = "FF00E1A4";
  $txt = "data/$user.txt";
  $log = "data/$user.log";
  $fh = fopen($txt, "r");
  if (!$fh) {
      header('Location: input.php');
      exit;
  }
  $listing = false;
  $cont = fread($fh, filesize($txt));
  fclose($fh);
  // $txt = mb_convert_encoding($txt, 'UTF-8', 'ISO-8859-1');
  $lines = explode("\n", $cont);
  $id = 100;
  $hr = false;
  foreach ($lines as $line) {
      $line = trim($line);
      ++$id;
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
              echo "<p><input type=checkbox id=$id onclick='note(this)'> <label for=$id>$line</label></p>";
              $hr = false;
          }
      }
  }
?>
</div>
</body></html>
