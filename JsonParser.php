<?php
abstract class JsonStatus
{
	const Normal = 0;
	const String = 1;
	const Number = 2;
	const Value = 3;
}
abstract class JsonPosition
{
	const Key=0;
	const Value=1;
}
abstract class JsonType
{
	const Null=0;
	const Integer=1;
	const Fraction=2;
	const Integer_Exponent=3;
	const Fraction_Exponent=4;
	const String=5;
	const Boolean=6;
	const Array=7;
	const Object=8;
}
class Json
{
	//private $array=array();
	public $array=array();
	//0:not begin
	//1:in begin
	//2:finish
	private $start=0;
	private $input=null;
	private $string="";
	//can use $offset for error log AND get current char!
	private $offset=0;//position of current pointer , current char!
	private $length=0;
	private $char='';
	private $char_prev='';
	private $char_prev_prev='';
	private $char_next='';
	private $char_next_next='';
	private $char_next_next_next='';
	private $char_next_next_next_next='';
	private $status=JsonStatus::Normal;
	private $str="";
	private $type=null;
	private $value="";
	private $value_type=null;
	private $number_x=1;
	private function string_escape($input)
	{
		$input=str_ireplace(array("\\r","\\n","\\r","\\n"),"\n",$input);
		//$input=str_replace("\\r\\n","\n",$input);
		//$input=str_replace("\\r","\n",$input);
		//$input=str_replace("\\n","\n",$input);
		//$input=str_replace("\\s"," ",$input);
		$input=str_replace("\\t","\t",$input);
		$input=str_replace("\\'","'",$input);
		$input=str_replace("\\\"","\"",$input);
		return $input;
	}
	private function is_number($input)
	{
		return is_numeric($input);	
	}
	private function is_skip($char)
	{
		//true or false
		return $this->char == ' ' || $this->char == '\t' || $this->char == '\n' || $this->char == '\r';
	}
	private function item_append($key,$value,$type)
	{
		switch($type)
		{
			case JsonType::Null:
			break;
			case JsonType::Integer:
				$value=(int) $value;
			break;
			case JsonType::Fraction:
				$value=(float) $value;
			break;
			case JsonType::Integer_Exponent:
				$value=(int) $value;
			break;
			case JsonType::Fraction_Exponent:
				$value=(float) $value;
			break;
			case JsonType::String:
				//any
			break;
			case JsonType::Boolean:
				$value=(bool) ($value == "true" ? true : false);
			break;
			case JsonType::Array:
				//soon
			break;
			case JsonType::Object:
				//soon
			break;
		}
		$this->array[$key]=$value;
	}
	public function decode($input)
	{
		$this->array=array();
		$input=trim($input);
		$this->string=$input;
		$this->offset=0;
		$this->length=mb_strlen($input,'UTF-8');
		for(;$this->offset<$this->length;$this->offset++)
		{
			$this->char=mb_substr($this->string,$this->offset,1);
			if($this->offset != 0)
			{
				$this->char_prev=mb_substr($this->string,$this->offset-1,1);
			}
			if($this->offset >= 1)
			{
				$this->char_prev_prev=mb_substr($this->string,$this->offset-2,1);
			}
			if($this->offset+1 != $this->length)
			{
				$this->char_next=mb_substr($this->string,$this->offset+1,1);
			}
			if($this->offset+2 != $this->length)
			{
				$this->char_next_next=mb_substr($this->string,$this->offset+2,1);
			}
			if($this->offset+3 != $this->length)
			{
				$this->char_next_next_next=mb_substr($this->string,$this->offset+3,1);
			}
			if($this->offset+4 != $this->length)
			{
				$this->char_next_next_next_next=mb_substr($this->string,$this->offset+4,1);
			}			
			if($this->offset == 0 && ($this->char == '{' || $this->char == '['))
			{
				$this->start=1;
			}
			else if($this->start == 1)
			{
				if($this->status == JsonStatus::Normal)
				{
					if($this->char == ' ' || $this->char == '\t' || $this->char == '\n' || $this->char == '\r')
					{
						//skip
					}
					else if($this->char == '"')
					{
						$this->status=JsonStatus::String;
						$this->type=JsonPosition::Key;
					}
					else if($this->char == ':')
					{
						$this->status=JsonStatus::Value;
					}
				}
				else if($this->status == JsonStatus::Value)
				{
					if($this->is_skip($this->char))
					{
						//skip
					}
					else if($this->char == ',')
					{
						$this->item_append($this->key,$this->value,$this->value_type);
						//print "KKK : ". $this->key."\n";
						//print "Value : " . $this->value."\n";
						$this->value='';
						$this->value_type=null;
						$this->status=JsonStatus::Normal;
					}
					//close,finish
					else if($this->char == ']' || $this->char == '}')
					{
						$this->item_append($this->key,$this->value,$this->value_type);
						//print "Key : " . $this->key."\n";
						//print "Value : " . $this->value."\n";
						$this->value='';
						$this->value_type=null;
						$this->status=JsonStatus::Normal;
						$this->start=2;
						//print_r($this->array);
					}
					//sub list,object as value
					else if($this->char == '[' || $this->char == '{')
					{
						$continue=true;
						$offset=$this->offset;
						$sub_type=0;
						$index=0;
						$string="";
						while($continue==true)
						{
							if($offset+2 > $this->length)
							{
								$continue=false;
								//break;
							}
							$char=mb_substr($this->string,$offset,1);
							$char_prev=mb_substr($this->string,$offset-1,1);
							$string.=$char;
							if($index == 0 && $char == '[')
								$sub_type++;
							else if($index == 0 && $char == '{')
								$sub_type++;
							if($index != 0)
							{
								if($char_prev != '\\' && $char == ']')
								{
									$sub_type--;
								}
								else if($char_prev != '\\' && $char == '}')
								{
									$sub_type--;
								}
								if($sub_type == 0)
								{
									$this->status=JsonStatus::Normal;
									$this->start=0;//not begin
									$o_key=$this->key;
									$new_json=new json;
									//print " =====> ". $string;
									$value=$new_json->decode($string);
									$this->item_append($o_key,$new_json->array,JsonType::Array);
									$continue=false;
									//break;
								}
							}
							$offset++;
							$index++;
						}
						$this->offset+=$index;
						//item_append($this->key,$this->decode($this->char));
						//$this->start=1;
					}
					//<number>
					else if($this->value == '' && 
						(
							$this->is_number($this->char) ||
							$this->char == '-'/* && $this->is_number($this->char_next) */||
							$this->char == '.'/* && $this->is_number($this->char_next)*/
						)
					)
					{
						$this->value=$this->char;
						$this->status=JsonStatus::Number;
						$this->type=JsonPosition::Value;
					}
					//true
					else if($this->value == '' && strtolower($this->char) == 't' && strtolower($this->char_next) == 'r' && strtolower($this->char_next_next) == 'u' && strtolower($this->char_next_next_next) == 'e')
					{
						$this->offset+=3;
						$this->value="true";
						$this->value_type=JsonType::Boolean;
						$this->type=JsonPosition::Value;
					}
					//false
					else if($this->value == '' && strtolower($this->char) == 'f' && strtolower($this->char_next) == 'a' && strtolower($this->char_next_next) == 'l' && strtolower($this->char_next_next_next) == 's' && strtolower($this->char_next_next_next_next) == 'e')
					{
						$this->offset+=4;
						$this->value="false";
						$this->value_type=JsonType::Boolean;
						$this->type=JsonPosition::Value;
					}
					else if($this->value == '' && $this->char == '"')
					{
						$this->value_type=JsonType::String;
						$this->status=JsonStatus::String;
						$this->type=JsonPosition::Value;
					}
					else
					{
						exit("Error!\nunknowm character '". $this->char . "'");
					}
				}
				else if($this->status == JsonStatus::Number)
				{
					if($this->char == ',' || $this->char == ']' || $this->char == '}')
					{
						$this->offset--;
						$this->status=JsonStatus::Value;
					}
					else
					{
						if($this->value == '-')
						{
							$this->number_x=-1;
						}
						if($this->number_x == 1 && $this->char == '-')
						{
							exit("Error!\nCan not use `-` character between a number! , only can in the first!");
						}
						else if($this->number_x == -1 && $this->char == '-')
						{
							exit("Error!\nCan not use many time `-` character in a number!");
						}
						else if($this->value == '.')
						{
							$this->value="0.";
							$this->type=JsonType::Fraction;
						}
						else if($this->char == '.' && $this->type == JSONTYPE::Fraction)
						{
							exit("Error!\nCan not use many time `.` character in a number!");
						}
						else if($this->char == '.')
						{
							$this->value.='.';
							$this->type=JsonType::Fraction;
						}
						else if($this->is_number($this->char))
						{
							$this->value.=$this->char;
						}
						else
						{
							exit("Error!\nunknowm character '". $this->char . "' for a number!");
						}
					}
				}
				else if($this->status == JsonStatus::String)
				{
					if($this->char == '"' && $this->char_prev !='\\')
					{
						//require : 
						//print $this->str."\n";
						$this->str=$this->string_escape($this->str);
						if($this->type == JsonPosition::Key)
						{
							//print "Key : " . $this->str."\n";
							$this->key=$this->str;
							$this->status=JsonStatus::Normal;
						}
						else if($this->type == JsonPosition::Value)
						{
							//print "Value : " . $this->str."\n";
							$this->value=$this->str;
							$this->status=JsonStatus::Value;
						}
						$this->str="";
					}
					else
					{
						$this->str.=$this->char;
					}
				}
				print "---".$this->char."\n";
			}
		}
	}
	public function encode($input)
	{

	}
}
$json=new Json;
