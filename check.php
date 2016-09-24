<?php

$now = time();
$result = array();
$dollarKeys = array('預募金額', '實募金額', '社會福利支出', '社會慈善支出', '教育文化支出', '人道救援支出', '其他支出', '必要支出', '總支出');
foreach(glob(__DIR__ . '/solicitView_detail/*.json') AS $jsonFile) {
  $json = json_decode(file_get_contents($jsonFile), true);
  print_r($json); exit();
  foreach($dollarKeys AS $dollarKey) {
    if(isset($json[$dollarKey])) {
      $json[$dollarKey] = str_replace(array('元', ','), '', $json[$dollarKey]);
    }
  }
  $t = strtotime($json['勸募活動結束']);
  if(!empty($json['申請單位']) && $t < $now) {
    $key = $json['申請單位'];
    if(!isset($result[$key])) {
      $result[$key] = array(
        'org' => $key,
        'avg' => 0,
        'count' => 0,
        'total' => 0,
        'cases' => array(),
      );
    }
    $result[$key]['count'] += 1;
    $result[$key]['total'] += $json['實募金額'];
    $case = array();
    foreach($dollarKeys AS $dollarKey) {
      if(isset($json[$dollarKey])) {
        $case[$dollarKey] = $json[$dollarKey];
      }

    }
    $result[$key]['cases'][] = array(
      'title' => $json['活動名稱'],
      'result' => $case,
    );
  }
}

foreach($result AS $k => $v) {
  $result[$k]['avg'] = round($result[$k]['total'] / $result[$k]['count']);
}

usort($result, "cmp");

print_r($result);

function cmp($a, $b)
{
    if ($a['avg'] == $b['avg']) {
        return 0;
    }
    return ($a['avg'] > $b['avg']) ? -1 : 1;
}
