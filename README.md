# PHP JsonParser

**Tiny Library for parse JSON.**

JsonParser can encode and decode data in JSON format.

We provides a pure PHP solution to take a value and generates a string that represents that value in JSON format.

The package can also take a JSON encoded string and decodes it to retrieve the original data value.

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
- [x] Null
- [x] String
- [ ] Char 
- [x] Sub Array
- [x] Sub Object

## TODO

- Improve speed and performance (Speed does not reach `C Language`!)
  
## Usage

All public functions are accessible through the `$json` variable.

- `array $json->decode(string);`
- `string $json->encode(array);`

## Samples

To view the full details, run the [Example.php](https://github.com/BaseMax/JsonParser/blob/master/Example.php) file.

## JSON Grammar

You can check the **[JSONGrammar.txt](https://github.com/BaseMax/JsonParser/blob/master/JSONGrammar.txt)** file to view the JSON standard grammar.


## Performance

Competition between `json_encode(...)` and `$json->encode(...)`


The basic PHP functions are written in C itself.

Do not expect to reach it quickly.

However, there is always way for improvement.


## History

| Version Num | Date Begin | Date Finish |
| ----------- | ---------- | ----------- |
| 1.0 (first) | 2018-06-26 | 2018-06-26 |
| 1.1 | 2018-06-27 | 2019-03-13 |
| 2.2 | 2019-03-25 | - |

# License

JsonParser is licensed under the [GNU General Public License](https://github.com/BaseMax/JsonParser/blob/master/LICENSE).
