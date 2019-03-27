# Benchmark

## Encode

#### Orginal PHP function `json_encode()`

```
1th Time : 0.0014569759368896 elapsed
2th Time : 0.0028131008148193 elapsed
3th Time : 0.0013258457183838 elapsed
4th Time : 0.0026090145111084 elapsed
-------------------------------------
Sigma Time : 0.008204937
Average Time : 0.008204937 ÷ 4 = 0.002051234
```

[View Full Source Code](https://github.com/BaseMax/JsonParser/blob/master/Performance-php.php)

#### Own function `$json->encode()`

```
1th Time : 0.17581701278687 elapsed
2th Time : 0.17606496810913 elapsed
3th Time : 0.17591309547424 elapsed
4th Time : 0.17687296867371 elapsed
-----------------------------------
Sigma Time : 0.704668045
Average Time : 0.704668045 ÷ 4 = 0.176167011
```

[View Full Source Code](https://github.com/BaseMax/JsonParser/blob/master/Performance-own.php)

---------

## Decode

#### Orginal PHP function `json_decode()`

#### Own function `$json->decode()`

