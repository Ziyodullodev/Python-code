<?php
/**
 * @link  https://korrektor.uz
 * @link  https://github.com/korrektoruz/tokenize
*/
function createMap($parts=[]) {
    $checkArrIndex = function($arr, $i){
        return ( $i < strlen($arr) ) ? $arr[$i] : '';
    };

    $textmap = [];
    foreach ($parts as $k => $v) {
        $rem = $v;
        if ($k != 0) $textmap[] = "D";
        
        $l = mb_strlen($v);
        foreach (range(0, $l) as $k) {
            if( mb_strlen($rem) > 0 ){
                if ( $checkArrIndex($rem, 0) == "V" && $checkArrIndex($rem, 1) != "C" ){
                    $textmap[] = 1;
                    $rem = mb_substr($rem, 1);
                }else if( $checkArrIndex($rem, 0) == "V" && $checkArrIndex($rem, 1) == "C" && $checkArrIndex($rem, 2) == "V" ){
                    $textmap[] = 1;
                    $rem = mb_substr($rem, 1);
                }else if( $checkArrIndex($rem, 0) == "V" && $checkArrIndex($rem, 1) == "C" && $checkArrIndex($rem, 2) != "V" && $checkArrIndex($rem, 3) != "C" ){
                    $textmap[] = 2;
                    $rem = mb_substr($rem, 2);
                }else if( $checkArrIndex($rem, 0) == "V" && $checkArrIndex($rem, 1) == "C" && $checkArrIndex($rem, 2) == "C" && $checkArrIndex($rem, 3) != "V" ){
                    $textmap[] = 3;
                    $rem = mb_substr($rem, 3);
                }else if( $checkArrIndex($rem, 0) == "C" && $checkArrIndex($rem, 1) == "V" && $checkArrIndex($rem, 2) != "C"){
                    $textmap[] = 2;
                    $rem = mb_substr($rem, 2);
                }else if( $checkArrIndex($rem, 0) == "C" && $checkArrIndex($rem, 1) == "V" && $checkArrIndex($rem, 2) == "C" && $checkArrIndex($rem, 3) == "V"){
                    $textmap[] = 2;
                    $rem = mb_substr($rem, 2);
                }else if( $checkArrIndex($rem, 0) == "C" && $checkArrIndex($rem, 1) == "V" && $checkArrIndex($rem, 2) == "C" && $checkArrIndex($rem, 3) == "C" && $checkArrIndex($rem, 4) == "V" || $checkArrIndex($rem, 0) == "C" && $checkArrIndex($rem, 1) == "V" && $checkArrIndex($rem, 2) == "C" && $checkArrIndex($rem, 3) != "C" && $checkArrIndex($rem, 3) != "V"){
                    $textmap[] = 3;
                    $rem = mb_substr($rem, 3);
                }else if( $checkArrIndex($rem, 0) == "C" && $checkArrIndex($rem, 1) == "V" && $checkArrIndex($rem, 2) == "C" && $checkArrIndex($rem, 3) == "C" && $checkArrIndex($rem, 4) != "V"){
                    $textmap[] = 4;
                    $rem = mb_substr($rem, 4);
                }else if( $checkArrIndex($rem, 0) == "C" && $checkArrIndex($rem, 1) == "C" && $checkArrIndex($rem, 2) == "V" && $checkArrIndex($rem, 3) != "C"){
                    $textmap[] = 3;
                    $rem = mb_substr($rem, 3);
                }else if( $checkArrIndex($rem, 0) == "C" && $checkArrIndex($rem, 1) == "C" && $checkArrIndex($rem, 2) == "V" && $checkArrIndex($rem, 3) == "C" && $checkArrIndex($rem, 4) == "V"){
                    $textmap[] = 3;
                    $rem = mb_substr($rem, 3);
                }else if( $checkArrIndex($rem, 0) == "C" && $checkArrIndex($rem, 1) == "C" && $checkArrIndex($rem, 2) == "V" && $checkArrIndex($rem, 3) == "C" && $checkArrIndex($rem, 4) != "V" && $checkArrIndex($rem, 5) != "C"){
                    $textmap[] = 4;
                    $rem = mb_substr($rem, 4);
                }else if( $checkArrIndex($rem, 0) == "C" && $checkArrIndex($rem, 1) == "C" && $checkArrIndex($rem, 2) == "V" && $checkArrIndex($rem, 3) == "C" && $checkArrIndex($rem, 4) == "C" && $checkArrIndex($rem, 5) != "V"){
                    $textmap[] = 5;
                    $rem = mb_substr($rem, 5);
                }   
            }
        }
    }
    
    return $textmap;
}


function tokenize($word){
    $aCorrect = function($text){
        $text = strtolower( $text );
        $text = str_replace(['\'','`','‘',], '’', $text);
        $text = str_replace(['gʻ','gʼ','g’','g\'','g`','g‘'], 'ğ', $text);
        $text = str_replace(['oʻ','oʼ','o’','o\'','o`','o‘'], 'ŏ', $text);
        $text = str_replace(['sh'], 'š', $text);
        $text = str_replace(['ch'], 'č', $text);
        $text = str_replace(['ʻ', 'ʼ', '\'', '`', '‘'], '’', $text);
        return $text;
    };

    $iCorrect = function($text){
        $text = strtolower( $text );
        $text = str_replace(['ğ'], 'g‘', $text);
        $text = str_replace(['ŏ'], 'o‘', $text);
        $text = str_replace(['š'], 'sh', $text);
        $text = str_replace(['č'], 'ch', $text);
        return $text;
    };

    $rgx = '/^[abdefghijklmnopqrstuvxyzŏğšč’]+$/u';
    $word = $aCorrect($word);
    $word = trim($word);
    $text = preg_replace('/[aoueiŏ]/u', 'V', $word);
    $text = preg_replace('/[bdfghjklmnpqrstvxyzğšč]/u', 'C', $text);
    $parts = explode("’", $text, 1);
    $textmap = createMap($parts);
    $rem = $word;
    $r = "";
    for ($k=0; $k < count($textmap); $k++) { 
        $v = $textmap[$k];
        if ($v == "D") {
            $r .= "’";
            $rem = mb_substr($rem, 1);
        }else{
            $sl = mb_substr($rem, 0, $v);
            $rem = mb_substr($rem, $v);
            if($k == 0){
                $r .= $sl;
            }else{
                $r .= "-".$sl;
            }
        }
    }
    return preg_match($rgx, $word) ? $iCorrect($r) : FALSE;
}

echo tokenize('dunyo');