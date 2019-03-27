# PHP JsonParser
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


# License

JsonParser is licensed under the [GNU General Public License](https://github.com/BaseMax/JsonParser/blob/master/LICENSE).
