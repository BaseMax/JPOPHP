<?php
include "JsonParser.php";
$data=json_encode([1,2,3,4,5,"n\"am':=>[]{}e"=>"ali"]);
$data=json_encode([1,2,3,4,5,"name"=>"ali"]);
$data=json_encode(["name"=>"ali",110=>"police"]);
print $data."\n";
$array=$json->decode($data);
print($array);
