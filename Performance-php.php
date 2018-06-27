<?php
include "JsonParser.php";
$data='{"name":"Max","family":"Base"}';
$time = microtime(true);
for($i=1;$i<=10000;$i++)
{
	json_encode($data);
}
echo (microtime(true) - $time) . " elapsed\n";
/*
1th Time : 0.0014569759368896 elapsed
2th Time : 0.0028131008148193 elapsed
3th Time : 0.0013258457183838 elapsed
4th Time : 0.0026090145111084 elapsed
Sigma Time : 0.008204937
Average Time : 0.008204937 ÷ 4 = 0.002051234
*/
