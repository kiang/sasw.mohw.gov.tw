<?php

$now = time();
foreach(glob(__DIR__ . '/solicitView_detail/*.json') AS $jsonFile) {
  $json = json_decode(file_get_contents($jsonFile), true);
  $t = strtotime($json['勸募活動結束']);
  if($t < $now) {
    $json['預募金額'] = str_replace(array('元', ','), '', $json['預募金額']);
    $json['實募金額'] = str_replace(array('元', ','), '', $json['實募金額']);
    echo "{$json['活動名稱']} - " . round($json['實募金額'] / $json['預募金額'], 2) . "\n";
  }
}
