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
    else{
        return false;
    }
}
function encode($array){
    $response="";
    if(array() !== $array)
    {
        $array_type=isAssociative($array) ? "associative" : "sequential";
        if($array_type == "associative"){//object
            $response.="{";
        }else{
            $response.="[";
        }
        $count = count($array);
        $index = 0;
        foreach($array as $key=>$value){
            if($array_type == "associative"){//object
                $response.="\"";
                $response.=$key;
                $response.="\"";
                $response.=":";
            }
            if($value === true || $value === false){//bool
                $response.=$value;
            }
            else if($value === null){//null
                $response.=$value;
            }
            else if(is_numeric($value) === true){//number
                $response.=$value;
            }
            else if(is_string($value) === true){//string
                $response.="\"".$value."\"";
            }
            else if(is_array($value) === true){//array
                $response.=encode($value);
            }
            else{
                print "Error: Unknowm type!\n";
                break;
            }
            // if(is_array($value)){
            //     $response.=encode($value);
            // }else{
            //     $response.=encodeValue($value);
            // }
            if(++$index !== $count){
                $response.=",";
            }
        }
        if($array_type == "associative"){//object
            $response.="}";
        }else{
            $response.="]";
        }
    }
    // $response.="\n";
    return $response;
}
print encode([1,2,3,4])."\n";
print encode([1,2,[94,15,34,67],3,4,["name"=>"max"]])."\n";
print encode(["name"=>"max","age"=>49,"username"=>"BaseMax"])."\n";
print encode(["0"=>"max","1"=>49,"2"=>"BaseMax"])."\n";
print encode([0=>"max",1=>49,2=>"BaseMax"])."\n";
print encode([0=>"max","1.5"=>49,2=>"BaseMax"])."\n";


