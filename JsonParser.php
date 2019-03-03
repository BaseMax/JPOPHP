<?php
/**
*
* @Name : JsonParser
* @Version : 2.0
* @Programmer : Max
* @Date : 2019-03-03
* @Released under : https://github.com/BaseMax/JsonParser/blob/master/LICENSE
* @Repository : https://github.com/BaseMax/JsonParser
*
**/
$input=" {156}";
$length=strlen($input);
$index=0;
/*
Arrays are :
    associative or sequential
*/
function isAssociative(array $array){
    // if(array() === $array)
    //     return false;
    return array_keys($array) !== range(0,count($array) - 1);
}
function encodeValue($value){
    if($value === true || $value === false){//bool
        return $value;
    }
    else if($value === null){//null
        return $value;
    }
    else if(is_numeric($value) === true){//number
        return $value;
    }
    else if(is_string($value) === true){//string
        return "\"".$value."\"";
    }
    else if(is_array($value) === true){//array
        return encode($value);
    }
}
function encode($array){
    $response="";
    if(array() !== $array)
    {
        $array_type=isAssociative($array) ? "associative" : "sequential";
        if($array_type == "associative"){//object
            $response.="{";
            $count = count($array);
            $index = 0;
            foreach($array as $key=>$value){
                $response.="\"";
                $response.=$key;
                $response.="\"";
                $response.=":";
                $response.=encodeValue($value);
                // if(is_array($value)){
                //     $response.=encode($value);
                // }else{
                //     $response.=encodeValue($value);
                // }
                if(++$index !== $count){
                    $response.=",";
                }
            }
            $response.="}";
        }
        else{//array
            $response.="[";
            $count = count($array);
            $index = 0;
            foreach($array as $key=>$value){
                $response.=encodeValue($value);
                // if(is_array($value)){
                //     $response.=encode($value);
                // }else{
                //     $response.=encodeValue($value);
                // }
                if(++$index !== $count){
                    $response.=",";
                }
            }
            $response.="]";
        }
    }
    $response.="\n";
    return $response;
}
print encode([1,2,3,4]);
print encode([1,2,[94,15,34,67],3,4,["name"=>"max"]]);
