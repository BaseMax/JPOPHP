<?php
include "JsonParser.php";
//////////////////////////////////////////////////////////
$data=json_encode([1,2,3,4,5,"n\"am':=>[]{}e"=>"ali"]);
print $data."\n";
$array=$json->decode($data);
print($array);
