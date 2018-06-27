<?php
include "JsonParser.php";
$data=json_encode([1,2,3,4,5,"n\"am':=>[]{}e"=>"ali"]);
$data=json_encode(["name"=>"ali","int"=>110,"float"=>19.98,"-int"=>-110,"-float"=>-19.98,"bool"=>true]);
//$data=json_encode(["name"=>"ali","int"=>110,"float"=>19.98,"-int"=>-110,"-float"=>-19.98,"bool"=>true,"list"=>[1,2,3,4,5]]);
//$data=json_encode(["name"=>"ali","int"=>110,"float"=>19.98,"-int"=>-110,"-float"=>-19.98,"bool"=>true,"list"=>["name"=>"ali","family"=>"ahmadi"]]);
//print $data."\n";
//$array=$json->decode($data);
//$array=$json->decode('{"list":{"name":"ali","family":"ahmadi"}}');
$array=$json->decode('{"list":{"name":"ali","family":"ahmadi"},"age":18}');
//$array=$json->decode('{"name":"ali","int":110,"float":19.98,"-int":-110,"-float":-19.98,"bool":TRUE,"bbb":false}');
//print($array);
print_r($json->array);
