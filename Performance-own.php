<?php
include "JPOPHP.php";
$data='{"name":"Max","family":"Base"}';
$time = microtime(true);
for($i=1;$i<=10000;$i++)
{
	$json->decode($data);
}
echo (microtime(true) - $time) . " elapsed\n";
/*
1th Time : 0.44240999221802 elapsed
2th Time : 0.43391299247742 elapsed
3th Time : 0.44072198867798 elapsed
4th Time : 0.43309497833252 elapsed
Sigma Time : 1.750139952
Average Time : 1.750139952 ÷ 4 = 0.437534988
*/
