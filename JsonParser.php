<?php
abstract class JsonStatus
{
	const Normal = 0;
	const Key = 1;
	const Value = 2;
	const String = 3;
	const Thursday = 4;
	const Friday = 5;
	const Saturday = 6;
}
class Json
{
	//0:not begin
	//1:in begin
	//2:finish
	private $start=0;
	private $input=null;
	private $string="";
	private $offset=0;
	private $length=0;
	private $char='';
	private $char_prev='';
	private $char_prev_prev='';
	private $char_next='';
	private $char_next_next='';
	private $char_next_next_next='';
	private $char_next_next_next_next='';
	private $status=JsonStatus::Normal;
	private $key="";
	private $value="";
	public function decode($input)
	{
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
					if($this->char == '"')
					{
						$this->status=JsonStatus::Key;
					}
				}
				else if($this->status == JsonStatus::Key)
				{
					if($this->char == '"' && $this->char_prev !='\\')
					{
						//require : 
						print $this->key."\n";
						$this->key="";
						$this->status=JsonStatus::Normal;
					}
					else
					{
						if
						(
							//Windows uses CR+LF (\r\n)
							//Linux LF (\n)
							//OSX CR (\r)
							//http://php.net/manual/en/regexp.reference.escape.php
							$this->char == '\\' && $this->char_next != '' && 
							(
								$this->char_next == 'a' ||
								$this->char_next == 'c' && $this->char_next_next == 'x' ||
								$this->char_next == 'e' ||
								$this->char_next == 'f' ||
								$this->char_next == 'n' ||
								//$this->char_next == 'p' && $this->char_next_next != '' && $this->char_next_next_next !='' ||
								//$this->char_next == 'P' && $this->char_next_next != '' && $this->char_next_next_next !='' ||
								$this->char_next == 'r' ||
								$this->char_next == 'R' ||
								$this->char_next == 't' ||
								//$this->char_next == 'x' && $this->char_next_next != '' && $this->char_next_next_next !='' ||
								//$this->char_next == 'd' && $this->char_next_next != '' && $this->char_next_next_next !='' ||
								$this->char_next == '8' && $this->char_next_next == '1' ||
								$this->char_next == '3' && $this->char_next_next == '7' && $this->char_next_next_next == '7' ||
								$this->char_next == '1' && $this->char_next_next == '3' && $this->char_next_next_next == '3' ||
								$this->char_next == '0' && $this->char_next_next == '1' && $this->char_next_next_next == '1' && $this->char_next_next_next_next == '3' ||
								$this->char_next == '0' && $this->char_next_next == '1' && $this->char_next_next_next == '1' ||
								$this->char_next == '1' && $this->char_next_next == '1' ||
								$this->char_next == '7' ||
								$this->char_next == '4' && $this->char_next_next == '0' ||
								$this->char_next == '0' && $this->char_next_next == '4' && $this->char_next_next_next == '0' ||
								$this->char_next == 'd' ||
								$this->char_next == 'D' ||
								$this->char_next == 'h' ||
								$this->char_next == 'H' ||
								$this->char_next == 's' ||
								$this->char_next == 'S' ||
								$this->char_next == 'v' ||
								$this->char_next == 'V' ||
								$this->char_next == 'w' ||
								$this->char_next == 'W' ||
								$this->char_next == 'b' ||
								$this->char_next == 'B' ||
								$this->char_next == 'A' ||
								$this->char_next == 'Z' ||
								$this->char_next == 'z' ||
								$this->char_next == 'G' ||
								$this->char_next == '"' ||
								$this->char_next == '\''
							)
						)
						{
							if($this->char_next == 't')
							{
								$this->offset++;
								$this->key.="\t";
							}
							else if($this->char_next == 'n')
							{
								$this->offset++;
								$this->key.="\n";
							}
							else if($this->char_next == 'n')
							{
								$this->offset++;
								$this->key.="\n";
							}
							else if($this->char_next == '"')
							{
								$this->offset++;
								$this->key.="\"";
							}
							else if($this->char_next == '\'')
							{
								$this->offset++;
								$this->key.="\"";
							}
						}
						else
						{
							$this->key.=$this->char;
						}
					}
				}
			}
			//print $this->char."\n";
		}
	}
	public function encode($input)
	{

	}
}
$json=new Json;
