<?php
/**
*
* @Name : JPOPHP (Json Parser Object PHP)
* @Version : 2.2.1
* @Programmer : Max
* @Date : 2018-06-26, 2018-06-27, 2019-03-23, 2019-03-24, 2019-03-26, 2019-03-27, 2019-04-04
* @Released under : https://github.com/BaseMax/JPOPHP/blob/master/LICENSE
* @Repository : https://github.com/BaseMax/JPOPHP
*
**/
abstract class JsonType {
	// []
	const JsonArray=0;
	// {}
	const JsonObject=1;
}
abstract class TokenType {
	// end of file, end of command, end of input string
	const TokenEOF=-1;
	// {
	const TokenArrayOpen=0;
	// }
	const TokenArrayClose=1;
	// [
	const TokenObjectOpen=2;
	// ]
	const TokenObjectClose=3;
	// "...", '...'
	const TokenString=4;
	// <int>, <float>, -<int>, -<float>, ...
	const TokenNumber=5;
	// ,
	const TokenSplit=6;
	// :
	const TokenPair=7;
	// null
	const TokenNull=8;
	// true, false
	const TokenBool=9;
}
class Json {
	/**
	 * 
	 * Public variable for whole of the class
	 *
 	 */
	// token type of current state in decode()
	public $token=null;
	// length and last index of the input, it update using decode()
	public $length=0;
	// input string, it update using decode()
	public $input="";
	// current state and index of pointer at the input string
	public $index=0;

 	/**
 	 * @function typeToken($token)
 	 * argument: $token
 	 * return : @string
	 */
	function typeToken($token) {
		switch($token[0]) {
			// EOF
			case TokenType::TokenEOF:
				return "EOF";
			break;
			// [
			case TokenType::TokenArrayOpen:
				return "ArrayOpen";
			break;
			// ]
			case TokenType::TokenArrayClose:
				return "ArrayClose";
			break;
			// {
			case TokenType::TokenObjectOpen:
				return "ObjectOpen";
			break;
			// }
			case TokenType::TokenObjectClose:
				return "ObjectClose";
			break;
			// "...", '...'
			case TokenType::TokenString:
				return "String";
			break;
			// number
			case TokenType::TokenNumber:
				return "Number";
			break;
			// ,
			case TokenType::TokenSplit:
				return "Split";
			break;
			// :
			case TokenType::TokenPair:
				return "Pair";
			break;
			// unknowm, other!
			default:
				return "None";
			break;
		}
	}

 	/**
 	 * @function isAssociative($array)
 	 * argument: array $array
 	 * return : @bool
	 */
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

 	/**
 	 * @function encode($array)
 	 * argument: array $array
 	 * return : @string
	 */
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

	// function nextsIfWithSkips($characterIf,$token,$tok)
	// function nextsIfWithSkip($characterIf,$token,$tok)
	// function nextIfWithSkips($characterIf,$token,$tok)
	// function nextIfWithSkip($characterIf,$token,$tok)

 	/**
 	 * @function nextsIf($characterIf)
 	 * argument: char $characterIf
 	 * return : @void
	 */
	function nextsIf($characterIf) {
		$character=$this->input[$this->index];
		if(is_array($characterIf)) {
			while(in_array($character,$characterIf)) {
				if($this->index + 1 === $this->length) {
					break;
				}
				$this->index++;
				$character=$this->input[$this->index];
			}
		}
		else {
			while($character == $characterIf) {
				if($this->index + 1 === $this->length) {
					break;
				}
				$this->index++;
				$character=$this->input[$this->index];
			}
		}
	}

 	/**
 	 * @function nextIf($characterIf)
 	 * argument: char $characterIf
 	 * return : @void
	 */
	function nextIf($characterIf) {
		$character=$this->input[$this->index];
		if(is_array($characterIf)) {
			if(in_array($character,$characterIf)) {
				if($this->index + 1 === $this->length) {
					return;
					// break;
				}
				$this->index++;
				// $character=$this->input[$this->index];
			}
		}
		else {
			if($character == $characterIf) {
				if($this->index + 1 === $this->length) {
					return;
					// break;
				}
				$this->index++;
				// $character=$this->input[$this->index];
			}
		}
	}

 	/**
 	 * @function nextsIf($token,tok)
 	 * argument: token $token, tokentype $tok
 	 * return : @token
	 */
	function skip($token,$tok) {
		// print "---start\n";
		if($token[0] === $tok) {
			$token=$this->nextToken();
			// print "---next\n";
		}
		// print "---finish\n";
		return $token;
	}

 	/**
 	 * @function skips($token,tok)
 	 * argument: token $token, tokentype $tok
 	 * return : @token
	 */
	function skips($token,$tok) {
		// print "---start\n";
		while($token[0] === $tok) {
			$token=$this->nextToken();
			// print "---next\n";
		}
		// print "---finish\n";
		return $token;
	}

 	/**
 	 * @function nextToken()
 	 * argument: void
 	 * return : @token
	 */
	function nextToken() {
		if($this->index + 1 > $this->length) {
			return [TokenType::TokenEOF,null];
		}
		$character=$this->input[$this->index];
		while($character == ' ' || $character == '	' || $character == "\n") {
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
		// n, N
		else if($character === 'n' || $character === 'N') {
			$i=0;
			$i++;
			$character=$this->input[$this->index+$i];
			// u, U
			if($character === 'u' || $character === 'U') {
				$i++;
				$character=$this->input[$this->index+$i];
				// l, L
				if($character === 'l' || $character === 'L') {
					$i++;
					$character=$this->input[$this->index+$i];
					// l, L
					if($character === 'l' || $character === 'L') {
						$this->index++;
						$this->index++;
						$this->index++;
						$this->index++;

						return [TokenType::TokenNull,null];
					}
				}
			}
		}
		// t, T
		else if($character === 't' || $character === 'T') {
			$i=0;
			$i++;
			$character=$this->input[$this->index+$i];
			// r, R
			if($character === 'r' || $character === 'R') {
				$i++;
				$character=$this->input[$this->index+$i];
				// u, U
				if($character === 'u' || $character === 'U') {
					$i++;
					$character=$this->input[$this->index+$i];
					// e, E
					if($character === 'e' || $character === 'E') {
						$this->index++;
						$this->index++;
						$this->index++;
						$this->index++;
						return [TokenType::TokenBool,true];
					}
				}
			}
		}
		// f, F
		else if($character === 'f' || $character === 'F') {
			$i=0;
			$i++;
			$character=$this->input[$this->index+$i];
			// a, A
			if($character === 'a' || $character === 'A') {
				$i++;
				$character=$this->input[$this->index+$i];
				// l, L
				if($character === 'l' || $character === 'L') {
					$i++;
					$character=$this->input[$this->index+$i];
					// s, S
					if($character === 's' || $character === 'S') {
						$i++;
						$character=$this->input[$this->index+$i];
						// e, E
						if($character === 'e' || $character === 'E') {
							$this->index++;
							$this->index++;
							$this->index++;
							$this->index++;
							$this->index++;
							return [TokenType::TokenBool,false];
						}
					}
				}
			}
		}
		else if($character === '"' || $character === '\'') {
			$stype=null;
			if($character === '"') {
				$stype=1;
			}
			else if($character === '\'') {
				$stype=2;
			}
			$result="";
			$characterPrev="";
			$this->index++;
			$character=$this->input[$this->index];
			$characterNext=null;
			while(
				( $stype === 1 && $characterNext !== '"' ) ||
				( $stype === 2 && $characterNext !== '\'')
			) {
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
				// It added by me, not in the standard JSON!
				if($character === '\\' && $characterNext === '\'') {
					$this->index++;
					// $this->index++;
					$character=$characterNext;
					if($this->index+1 < $this->length) {
						$characterNext=$this->input[$this->index+1];
					}
					else {
						$characterNext=null;
					}
				}
				/**
				*
				* @Name : Unicode Support
				* @Description : This feature is requested by Frederick Behrends.
				* @Url, @Issue : https://github.com/BaseMax/JsonParser/issues/1
				*/
				else if($character === '\\' && $characterNext === 'u') {
					$unicode = '';
					$this->index++;
					$this->index++;
					$perform=true;
					$i=1; // We require it after the loop!
					for(;$i<=4;$i++) {
						// print "...\n";
						// if($perform === false) {
						// 	break;
						// }
						if($this->index+1 < $this->length) {
							$characterNext=$this->input[$this->index]; // As temp variable
							$perform=true;
							if(
								$characterNext >= '0' && $characterNext <= '9' ||
								$characterNext >= 'A' && $characterNext <= 'F'
							) {
								$character=$characterNext;// It will use when loop break! ($perform=false)
								$unicode.=$character;
								$this->index++;
							}
							else { // May be " character!
								// print "A stage\n";
								// print $unicode."\n";
								// print $character."\n";
								$perform=false;
								break;
							}
						}
						else {
							// print "B stage\n";
							$perform=false;
							break;
						}
					}
					if($perform === true) {
						$this->index--; // Required...
						// print "C Stage\n";
						// print $unicode."\n";
						$unicode="%u".$unicode;
						# $unicode = preg_replace('/%u([0-9A-F]){4}/', '&#x$1;', $unicode);
						$unicode = preg_replace('/%u([0-9A-F]+)/', '&#x$1;', $unicode);
						// ENT_COMPAT : Will convert double-quotes and leave single-quotes alone.
						// https://www.php.net/manual/en/function.htmlentities.php
						// print $unicode."\n";
						$character=html_entity_decode($unicode, ENT_COMPAT, 'UTF-8');
						// print $character."\n";
					}
					else {
						// Last index is $i
						// We ($i-1) time rub the $index++
						// print $i."\n";
						// for($ii=1;$ii<$i-1;$ii++) {
						// 	$this->index--;
						// }
						$this->index--;
						$character="\\u".$unicode;
					}
					if($this->index+1 < $this->length) {
						$characterNext=$this->input[$this->index+1];
					}
					else {
						$characterNext=null;
					}
				}
				else if($character === '\\' && $characterNext === 'n') {
					$this->index++;
					// $this->index++;
					$character="\n";
					if($this->index+1 < $this->length) {
						$characterNext=$this->input[$this->index+1];
					}
					else {
						$characterNext=null;
					}
				}
				else if($character === '\\' && $characterNext === '\\') {
					$this->index++;
					// $this->index++;
					$character="\\";
					if($this->index+1 < $this->length) {
						$characterNext=$this->input[$this->index+1];
					}
					else {
						$characterNext=null;
					}
				}
				else if($character === '\\' && $characterNext === '/') {
					$this->index++;
					// $this->index++;
					$character="/";
					if($this->index+1 < $this->length) {
						$characterNext=$this->input[$this->index+1];
					}
					else {
						$characterNext=null;
					}
				}
				else if($character === '\\' && $characterNext === 't') {
					$this->index++;
					// $this->index++;
					$character="\t";
					if($this->index+1 < $this->length) {
						$characterNext=$this->input[$this->index+1];
					}
					else {
						$characterNext=null;
					}
				}
				else if($character === '\\' && $characterNext === 'r') {
					$this->index++;
					// $this->index++;
					$character="\r";
					if($this->index+1 < $this->length) {
						$characterNext=$this->input[$this->index+1];
					}
					else {
						$characterNext=null;
					}
				}
				else if($character === '\\' && $characterNext === 'b') {
					$this->index++;
					// $this->index++;
					$character="\b";
					if($this->index+1 < $this->length) {
						$characterNext=$this->input[$this->index+1];
					}
					else {
						$characterNext=null;
					}
				}
				else if($character === '\\' && $characterNext === '"') {
					$this->index++;
					// $this->index++;
					$character=$characterNext;
					// Fix: "hi\"!"
					if($this->index+1 < $this->length) {
						$characterNext=$this->input[$this->index+1];
					}
					else {
						$characterNext=null;
					}
				}
				// else {
				// 	$this->index++;
				// }

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
		// <int>(0 .. 9), -, .
		// Allow : .9, .04
		// Allow : -5
		// Allow : -5.048
		else if(($character >='0' && $character <='9') || $character == '-' || $character == '.') {
			$result=0;
			$bitflag=false;
			$bitfloat=false;
			$bitfloatindex=0;
			// while($character >='0' && $character <='9') {
			while(
				($character >='0' && $character <='9') || 
				$character == '-' ||
				$character == '.'
			) {
				if($this->index == $this->length) {
					break;
				}
				// $result*=10+(int)$character;
				if($bitflag === false && $character === '-') {
					$bitflag=true;
				}
				else if($bitflag === true && $character === '-') {
					// Error
					exit("Aleady expression has a minus!\n");
				}
				else {
					if($bitfloat === false && $character == '.') {
						$bitfloat=true;
						// $bitfloatindex=0;
					}
					else if($bitflag === true && $character == '.') {
						// Error
						exit("Aleady expression was a float type!\n");
					}
					// else if($bitflag === true && ($character == 'e' || $character == 'E')) {
					else if($character == 'e' || $character == 'E') {
						//soon
						exit("Soon, E+5 likely expression will develope....!\n");
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
				if($this->index+1 < $this->length) {
					$character=$this->input[$this->index];
				}
				else {
					$character=null;
				}
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

 	/**
 	 * @function isValue(token)
 	 * argument: token $token
 	 * return : array[bool $status, string $result]
	 */
	function isValue($token) {
		/**
		 * Values:
		 *			<int>, <float>, - <int>, - <float>, -<int>(e|E)(+|-)<int>, <int>(e|E)(+|-)<int>, -<float>(e|E)(+|-)<int>, <float>(e|E)(+|-)<int>
		 *
		 *			<bool> (true, false)
		 *
		 *			<null> (null)
		 *
		 *			<string> ("...", '...')
		 *
		 *			<object> {...}
		 *
		 *			<array> [...]
		*/
		if($token[0] === TokenType::TokenNumber) {
			return [true,$token[1]];
			// return [true,null];
			// return true;
		}
		else if($token[0] === TokenType::TokenString) {
			return [true,$token[1]];
			// return [true,null];
			// return true;
		}
		else if($token[0] === TokenType::TokenBool) {
			return [true,$token[1]];
		}
		else if($token[0] === TokenType::TokenNull) {
			return [true,null];
		}
		else if($token[0] === TokenType::TokenObjectOpen) {
			// $tree=0;
			// $result=[];
			$this->index--;
			// print "...\n";
			$result=$this->decode(null,false);
			// while($token[0] != TokenType::TokenObjectClose) {
				
			// }
			// print_r($result);
			return [true,$result];
			// return true;
		}
		else if($token[0] === TokenType::TokenArrayOpen) {
			// $tree=0;
			// $result=[];
			$this->index--;
			// print "...\n";
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

 	/**
 	 * @function decode(input,init=true)
 	 * argument: string $input, bool init
 	 * return : array[...]
	 */
	function decode($input,$init=true) {
		if($init === true) {
			// $this->tree=[];
			// $this->trees=[];
			$this->index=0;
			$this->input=$input;
			$this->length=mb_strlen($input);
			// $this->tree=null;
		}
		else {
			// $this->tree=0;
		}
		$result=[];
		$this->token=$this->nextToken();
		// print_r($token);
		// $arrayOpen=false;
		// $objectOpen=false;
		// // skip spaces
		// $this->nextsIf([" ","\n","	"]);
		if(
			$this->token[0] === TokenType::TokenArrayOpen ||
			$this->token[0] === TokenType::TokenObjectOpen
		) {
			$type=null;
			if($this->token[0] === TokenType::TokenArrayOpen) {
				$type=JsonType::JsonArray;
			}
			else if($this->token[0] === TokenType::TokenObjectOpen) {
				$type=JsonType::JsonObject;
			}
			// $this->tree[]=$this->token[0];
			$this->token=$this->nextToken();
			// // skip spaces
			// $this->nextsIf([" ","\n","	"]);
			// // skip split
			// $this->skips($token,TokenType::TokenSplit);
			// // skip spaces
			// $this->nextsIf([" ","\n","	"]);
			// // skip space(s) and split(s)
			// $this->nextsIfWithSkips([" ","\n","	"],$token,TokenType::TokenSplit);
			// skip split
			// print_r($token);
			$this->token=$this->skips($this->token,TokenType::TokenSplit);
			// print_r($token);
			// exit;
			// parse until arrayClose
			while(
				( $type === JsonType::JsonArray && $this->token[0] !== TokenType::TokenArrayClose ) ||
				( $type === JsonType::JsonObject && $this->token[0] !== TokenType::TokenObjectClose )
			) {
				// print "==>".$this->typeToken($this->token) ."\n";
				if($this->token[0] === TokenType::TokenEOF) {
					exit("Command is finish, but arrayClose not found!\n");
				}
				$first=null;
				$second=null;
				$first=$this->isValue($this->token);
				if($first[0] === true) {
					// print "----yes\n";
					// $first=$token;
					// $first=$this->token;
					// next may be was pair or split or arrayClose or EOF!
					$this->token=$this->nextToken();
					// print "\t==>".$this->typeToken($token) ."\n";
					if($type === JsonType::JsonObject) {
						if($this->token[0] === TokenType::TokenPair) {
							if(is_string($first[1]) === true) {
								$this->token=$this->nextToken();
								// $second=$this->token;
								$second=$this->isValue($this->token);
								if($second[0] === true) {
									$this->token=$this->nextToken();
								}
								else {
									// Error!
									exit("Unknowm token, pair value is not a value!\n");
								}
							}
							else {
								// Error!
								exit("Unknowm token, key of pair value is not a string!\n");
							}
						}
						else {
							// Error!
							exit("Unknowm token, all item of object should was a pair value!\n");
						}
					}
					// its a array JSON
					if($second === null) {
						/**
						 * result[index]
						 *	=
						 *	<value> (first)
						 */
						$result[]=$first[1];
					}
					// its a object JSON
					else {
						/**
						 * result[
						 *		<value> (first)
						 *		]
						 *	=
						 *	<value> (second)
						 */
						$result[$first[1]]=$second[1];
					}
					// print_r($result);
					$this->token=$this->skips($this->token,TokenType::TokenSplit);
					// $this->skips($token,TokenType::TokenSplit);
				}
				else {
					// print "----no\n";
					// print_r($token);
					// print $this->input."\n";
					// print $this->index."\n";
					// print $this->input[$this->index]."\n";
					$this->token=$this->nextToken();
					// print_r($token);
				}
				// print "\t==>".$this->typeToken($token) ."\n";
				// print_r($token);
			}
		}
		// else if($this->token[0] === TokenType::TokenObjectOpen) {
		// 	// $this->tree[]=$this->token[0];
		// }
		else if($this->token[0] === TokenType::TokenEOF) {
			// ;
			// return;
			// exit;
		}
		else {
			// Error!
			exit("Unknowm token at the begin of command!\n");
		}
		// print_r($result);
		return $result;
	}
}
$json=new Json;
// // $data=json_encode([1,2,3,4,5,"n\"am':=>[]{}e"=>"ali"]);
// // $data=json_encode(["name"=>"ali","int"=>110,"float"=>19.98,"-int"=>-110,"-float"=>-19.98,"bool"=>true]);
// //$data=json_encode(["name"=>"ali","int"=>110,"float"=>19.98,"-int"=>-110,"-float"=>-19.98,"bool"=>true,"list"=>[1,2,3,4,5]]);
// //$data=json_encode(["name"=>"ali","int"=>110,"float"=>19.98,"-int"=>-110,"-float"=>-19.98,"bool"=>true,"list"=>["name"=>"ali","family"=>"ahmadi"]]);
// //print $data."\n";
// //$array=$json->decode($data);
// //$array=$json->decode('{"list":{"name":"ali","family":"ahmadi"}}');
// $array=$json->decode('{"list":{"name":"ali","family":"ahmadi"},"age":18}');
// $array=$json->decode('{"list":{"name":"ali","family":"ahmadi"},"age":18.....2}');
// $array=$json->decode('{"list":{"name":"ali","family":"ahmadi"},"age":097}');
// //$array=$json->decode('{"name":"ali","int":110,"float":19.98,"-int":-110,"-float":-19.98,"bool":TRUE,"bbb":false}');
// print_r($array);
// //print_r($json->array);
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
// print_r($json->decode('[,,,,]'));
// print_r($json->decode('[4]'));
// print_r($json->decode(' [,,,,4]'));
// print_r($json->decode(' [  , , , , 4]'));
// print_r($json->decode(' [  , , , , 4 ]    '));
// print_r($json->decode(' [  , , 	,	 , 	4	 ]    '));
// print_r($json->decode("[\n]"));
// print_r($json->decode('["max",49,"BaseMax"]'));
// print_r($json->decode('[1,[]]'));
// print_r($json->decode('[1,[4]]'));
// print_r($json->decode('[1,[4,,,6,[8]]]'));
// print_r($json->decode('{"name":"Max","line":456,,,,}'));
// print_r($json->decode('{"name":"Max","line":456,,,,}'));
// print_r($json->decode('{"name":"Max",4:555,,,,}'));
// print_r($json->decode('[null,false,true]'));
// $res=($json->decode('[null,false,true]'));
// var_dump($res[1]);
// var_dump($res);
// var_dump($json->decode('[null,false,true,]'));
// print_r($json->decode("[\"45\\\\\"]"));
// $arg='["4\\\\"]';
// var_dump($arg);
// print $arg."\n";
// print_r($json->decode($arg));
// print_r($json->decode('["1\u05B5"]'));
// print_r($json->decode('["1\u05F1"]'));
// print_r($json->decode('["1\u05F"]'));
// print_r($json->decode('["1\u05FX"]'));
// print_r($json->decode('["1\uM05F"]'));
// print_r($json->decode('["1\uM0\'5F"]'));
