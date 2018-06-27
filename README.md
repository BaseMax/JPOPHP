# JsonParser
Tiny Library for parse JSON.

![JsonParser.min.php File Size](https://img.shields.io/badge/Compressed%20Size-6.6%20KB-blue.svg) ![JsonParser.min.php Validation Code](https://img.shields.io/badge/Validation%20Code-No%20Error-green.svg)


## Features 
  - Small and light library
  - Hand-coded scanner
  - Analyze and found Errors
  - Poor speed and performance
  - No dependencies (only suitable version of the `PHP`)
  - Written and run on `PHP 7.2.6`


## Type of supported values
  
  - [x] Integer
  - [x] Float
  - [x] Boolean (`true` , `false`)
  - [x] String
  - [ ] Char 
  - [x] Sub Array
  - [ ] Sub Object

## Unfinished items
  
  - `decode()` function
  - Improve speed and performance (Speed does not reach `C Language`!)
  - Fix bugs of arrays in arrays (Tree Array)
  

## Usage

All public functions are accessible through the $ json variable.

- `array $json->decode(string);`
- `string $json->encode(array);`



| Function | Return Type | Argument |
| -------- | ----------- | -------- |
| encode() |     Array   |  String  |
| decode() |     String  |  Array   |




## Samples

To view the full details, run the [Example.php](https://github.com/BaseMax/JsonParser/blob/master/Example.php) file.

## Performance


Competition between `json_encode(...)` and `$json->encode(...)`


The basic PHP functions are written in C itself.

Do not expect to reach it quickly.

However, there is always way for improvement.


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
1th Time : 0.44240999221802 elapsed
2th Time : 0.43391299247742 elapsed
3th Time : 0.44072198867798 elapsed
4th Time : 0.43309497833252 elapsed
-----------------------------------
Sigma Time : 1.750139952
Average Time : 1.750139952 ÷ 4 = 0.437534988
```

[View Full Source Code](https://github.com/BaseMax/JsonParser/blob/master/Performance-own.php)


# License

JsonParser is licensed under the [GNU General Public License](https://github.com/BaseMax/JsonParser/blob/master/LICENSE).
