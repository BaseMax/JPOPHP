<?php
/**
*
* @Name : JsonParser
* @Version : 2.1
* @Programmer : Max
* @Date : 2018-06-26, 2018-06-27, 2019-03-23, 2019-03-24, 2019-03-26
* @Released under : https://github.com/BaseMax/JsonParser/blob/master/LICENSE
* @Repository : https://github.com/BaseMax/JsonParser
*
**/
abstract class JsonType {
	const JsonArray=0;
	const JsonObject=1;
}
abstract class TokenType {
	const TokenEOF=-1;
	const TokenArrayOpen=0;
	const TokenArrayClose=1;
	const TokenObjectOpen=2;
	const TokenObjectClose=3;
	const TokenString=4;
	const TokenNumber=5;
	const TokenSplit=6;
	const TokenPair=7;
}
class Json {
	/*
	 * Arrays are :
	 * associative or sequential
	 */
	function isAssociative(array $array) {
		// if(array() === $array)
		//     return false;
		return array_keys($array) !== range(0,count($array) - 1);
	}
	// function encodeValue($value) {
	//     if($value === true || $value === false) {//bool
	//         return $value;
	//     }
	//     else if($value === null) {//null
	//         return $value;
	//     }
	//     else if(is_numeric($value) === true) {//number
	//         return $value;
	//     }
	//     else if(is_string($value) === true) {//string
	//         return "\"".$value."\"";
	//     }
	//     else if(is_array($value) === true) {//array
	//         return encode($value);
	//     }
	//     else {
	//         return false;
	//     }
	// }
	function encode($array) {
		$response="";
		if(array() !== $array) {
			$array_type=$this->isAssociative($array) ? "associative" : "sequential";
			if($array_type == "associative") {//object
				$response.="{";
			}
			else {
				$response.="[";
			}
			$count = count($array);
			$index = 0;
			foreach($array as $key=>$value) {
				if($array_type == "associative") {//object
					$response.="\"";
					$response.=$key;
					$response.="\"";
					$response.=":";
				}
				if($value === true || $value === false) {//bool
					$response.=$value;
				}
				else if($value === null) {//null
					$response.=$value;
				}
				else if(is_numeric($value) === true) {//number
					$response.=$value;
				}
				else if(is_string($value) === true) {//string
					$response.="\"".$value."\"";
				}
				else if(is_array($value) === true) {//array
					$response.=$this->encode($value);
				}
				else {
					print "Error: Unknowm type!\n";
					break;
				}
				// if(is_array($value)) {
				//     $response.=this->encode($value);
				// }
				// else {
				//     $response.=encodeValue($value);
				// }
				if(++$index !== $count) {
					$response.=",";
				}
			}
			if($array_type == "associative") {//object
				$response.="}";
			}
			else {
				$response.="]";
			}
		}
		// $response.="\n";
		return $response;
	}
	public $length=0;
	public $input="";
	public $index=0;
	function nextToken() {
		if($this->index + 1 > $this->length) {
			return [TokenType::TokenEOF,null];
		}
		$character=$this->input[$this->index];
		while($character == ' ' || $character == '	' || $character == '\n') {
			if($this->index + 1 === $this->length) {
				break;
			}
			$this->index++;
			$character=$this->input[$this->index];
		}
		if($character == '{') {
			$this->index++;
			return [TokenType::TokenObjectOpen,null];
		}
		else if($character == '}') {
			$this->index++;
			return [TokenType::TokenObjectClose,null];
		}
		else if($character == '[') {
			$this->index++;
			return [TokenType::TokenArrayOpen,null];
		}
		else if($character == ']') {
			$this->index++;
			return [TokenType::TokenArrayClose,null];
		}
		else if($character == ',') {
			$this->index++;
			return [TokenType::TokenSplit,null];
		}
		else if($character == ':') {
			$this->index++;
			return [TokenType::TokenPair,null];
		}
		else if($character == '"') {
			$result="";
			$characterPrev="";
			$this->index++;
			$character=$this->input[$this->index];
			$characterNext=null;
			while($characterNext != '"') {
				if($this->index == $this->length) {
					break;
				}
				$character=$this->input[$this->index];
				if($this->index+1 < $this->length) {
					$characterNext=$this->input[$this->index+1];
				}
				else {
					$characterNext=null;
				}
				if($character == '\\' && $characterNext == '"') {
					$this->index++;
					$character=$characterNext;
					//Fix: "hi\"!"
					if($this->index+1 < $this->length) {
						$characterNext=$this->input[$this->index+1];
					}
					else {
						$characterNext=null;
					}
				}
				// else if($character == '"') {
				// 	// $this->index--;
				// 	// $this->index--;
				// 	// $character='"';
				// 	break;
				// 	continue;
				// }
				$result.=$character;
				$this->index++;
			}
			$this->index++;
			return [TokenType::TokenString,$result];
		}
		else if(($character >='0' && $character <='9') || $character == '-' || $character == '.') {
			$result=0;
			$bitflag=false;
			$bitfloat=false;
			$bitfloatindex=0;
			// while($character >='0' && $character <='9') {
			while(($character >='0' && $character <='9') || $character == '-' || $character == '.') {
				if($this->index == $this->length) {
					break;
				}
				// $result*=10+(int)$character;
				if($bitflag === false && $character === '-') {
					$bitflag=true;
				}
				else if($bitflag === true && $character === '-') {
					// Error
				}
				else {
					if($bitfloat === false && $character == '.') {
						$bitfloat=true;
						// $bitfloatindex=0;
					}
					else if($bitflag === true && $character == '.') {
						// Error
					}
					else if($bitflag === true && ($character == 'e' || $character == 'E')) {
						//soon
					}
					else if($bitfloat === true) {
						$bitfloatindex++;
						$floatcurrent=pow(10,$bitfloatindex);
						$result=$result + ((int)$character / $floatcurrent);
					}
					// else if($bitfloat === false) {
					else {
						$result=$result * 10;
						$result=$result + (int)$character;
					}
				}
				$this->index++;
				$character=$this->input[$this->index];
			}
			if($bitflag === true) {
				$result*=-1;
			}
			// $this->index++;
			return [TokenType::TokenNumber,$result];
		}
		else {
			$this->index++;
		}
		return [TokenType::TokenEOF,null];
	}
	function isValue($token) {
		if($token[0] === TokenType::TokenNumber) {
			return [true,null];
			// return true;
		}
		else if($token[0] === TokenType::TokenString) {
			return [true,null];
			// return true;
		}
		else if($token[0] === TokenType::TokenObjectOpen) {
			// $tree=0;
			// $result=[];
			$result=$this->decode(null,false);
			// while($token[0] != TokenType::TokenObjectClose) {
				
			// }
			return [true,$result];
			// return true;
		}
		else if($token[0] === TokenType::TokenArrayOpen) {
			// $tree=0;
			// $result=[];

			// print $this->input."\n";
			// print $this->index."\n";
			// print $this->input[$this->index]."\n";

			$result=$this->decode(null,false);
			// while($token[0] != TokenType::TokenObjectClose) {
			//	
			// }
			return [true,$result];
			// return true;
		}
		return [false,null];
		// return false;
	}
	function decode($input,$init=true) {
		if($init === true) {
			$this->index=0;
			$this->input=$input;
			$this->length=mb_strlen($input);
			// $this->tree=null;
		}
		else {
			// $this->tree=0;
		}
		$result=[];
		$token=$this->nextToken();
		// print_r($token);
		// $arrayOpen=false;
		// $objectOpen=false;
		$type=null;
		while($token[0] !== TokenType::TokenEOF) {
			// $result[]=$token[0]." => ".$token[1]."\n";
			// $result.=$token[0]." => ".$token[1]."\n";
			// $result[]=$token[0]." => ".$token[1];
			// [
			if($token[0] === TokenType::TokenArrayOpen || $token[0] === TokenType::TokenObjectOpen) {
				// $arrayOpen=true;
				if($token[0] === TokenType::TokenArrayOpen) {
					$type=JsonType::JsonArray;
					// $this->tree++;
					print "--> Open\n";
					$this->tree[]=$type;
				}
				else if($token[0] === TokenType::TokenObjectOpen) {
					$type=JsonType::JsonObject;
					// $this->tree++;
					print "--> Open\n";
					$this->tree[]=$type;
				}
				$token=$this->nextToken();
				$a=[];
				print_r($token);
				if($type === JsonType::JsonArray && $token[0] === TokenType::TokenArrayClose) {
					// ;
					// $this->tree--;
					print "--> Close\n";
					unset($this->tree[count($this->tree)-1]);
					if(count($this->tree) == 0) {
						return $a;
					}
				}
				else if($type === JsonType::JsonObject && $token[0] === TokenType::TokenObjectClose) {
					// ;
					// $this->tree--;
					print "--> Close\n";
					unset($this->tree[count($this->tree)-1]);
					if(count($this->tree) == 0) {
						return $a;
					}
				}
				while(
					($type === JsonType::JsonObject && $token[0] != TokenType::TokenObjectClose) ||
					($type === JsonType::JsonArray && $token[0] != TokenType::TokenArrayClose)
				) {
					$first=null;
					$second=null;
					$haskey=false;
					if($token[0] === TokenType::TokenSplit) {
						$token=$this->nextToken();
						while($token[0] === TokenType::TokenSplit) {
							$token=$this->nextToken();
						}
						continue;
					}
					if($this->isValue($token)) {
						$first=$token;
					}
					else {
						// Error
						exit("NoneValue founded!\n");
					}
					$token=$this->nextToken();
					// Only allowed for object, not array!
					if($type === JsonType::JsonObject) {
						if($token[0] == TokenType::TokenPair) {
							$token=$this->nextToken();
							if($first[0] === TokenType::TokenString) {// Pair key must be string
								if($this->isValue($token)) {
									$second=$token;
									$haskey=true;
								}
								else {
									// Error
									exit("NoneValue as PairValue!\n");
								}
								$token=$this->nextToken();
								// print_r($token);
							}
							else {
								// Error
								exit("PairKey Was not String!\n");
							}
						}
						else {
							// print_r($token);
							// Error
							exit("All item of object should was pair value (both of the key and value)!\n");
						}
					}
					// else if($arrayOpen)
					if($token[0] == TokenType::TokenSplit) {
						$token=$this->nextToken();
						while($token[0] === TokenType::TokenSplit) {
							$token=$this->nextToken();
						}
					}
					else {
						// print_r($a);
						// exit("Error!\n");
						// // Error
						// // May be last item of the array
						if($type === JsonType::JsonArray && $token[0] === TokenType::TokenArrayClose) {
							// ;
							// $this->tree--;
							print "--> Close\n";
							unset($this->tree[count($this->tree)-1]);
							if(count($this->tree) == 0) {
								return $a;
							}
						}
						else if($type === JsonType::JsonObject && $token[0] === TokenType::TokenObjectClose) {
							// ;
							// $this->tree--;
							print "--> Close\n";
							unset($this->tree[count($this->tree)-1]);
							if(count($this->tree) == 0) {
								return $a;
							}
						}
						else {
							print_r($this->tree);
							// print_r($a);
							// print_r($token);
							exit("Error!\n");
						}
					}
					if($haskey === false) {
						$a[]=$first[1];
					}
					else {
						$a[$first[1]]=$second[1];
					}
				}
				$result=$a;
				// $token[0]." => ".$token[1];
				// $arrayOpen=false;
			}
			$token=$this->nextToken();
		}
		return $result;
	}
}
$json=new Json;
// print $json->encode([1,2,3,4])."\n";
// print $json->encode([1,2,[94,15,34,67],3,4,["name"=>"max"]])."\n";
// print $json->encode(["name"=>"max","age"=>49,"username"=>"BaseMax"])."\n";
// print $json->encode(["0"=>"max","1"=>49,"2"=>"BaseMax"])."\n";
// print $json->encode([0=>"max",1=>49,2=>"BaseMax"])."\n";
// print $json->encode([0=>"max","1.5"=>49,2=>"BaseMax"])."\n";
// print "\n\n";
// print $json->decode('[]')."\n";
// print $json->decode('{}')."\n";
// print $json->decode('["1"]')."\n";
// print $json->decode('["123456"]')."\n";
// print $json->decode('["hi"]')."\n";
// print $json->decode('["hi\""]')."\n";
// print $json->decode('["hi\"!"]')."\n";
// print $json->decode('[13]')."\n";
// print $json->decode('[134]')."\n";
// print $json->decode('[8]')."\n";
// print $json->decode('[0]')."\n";
// print $json->decode('[-9]')."\n";
// print $json->decode('[-945]')."\n";
// print $json->decode('[3.1]')."\n";
// print $json->decode('[3.145]')."\n";
// print_r($json->decode('			  [  -3.145,4,"test"]'));
// print_r($json->decode('			  [  -3.145,4,"test","name":"max"]'));
// print_r($json->decode('			  [  -3.145,4,"name","max",]'));
// print_r($json->decode('			  [  -3.145,4,"name","max",,,,,,,]'));
// print_r($json->decode('			  [,]'));
// print_r($json->decode('			  [,,,,]'));
// print_r($json->decode('			  [,,,,4]'));
// print_r($json->decode('{}'));
// print_r($json->decode('{"a":4,"6":945,,,}'));
// print_r($json->decode('{"a":4,"b":456,,,,}'));
// print_r($json->decode('{,"a":4,}'));
// print_r($json->decode('{,,,"a":4,"b":456,,,,}'));
// print_r($json->decode('{,,,,"a":4,,,,,,"6":945,,,}'));
// print_r($json->decode('[1,]'));
// print_r($json->decode('[4]'));
// print $json->decode('["max",49,"BaseMax"]')."\n";
