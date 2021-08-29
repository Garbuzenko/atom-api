<?php

mysql_connect('localhost', 'goodlijy_atom', 'z%LwU0lJ') or die("Ошибка подключения к базе данных!");
mysql_select_db('goodlijy_atom') or die("Нет доступа к базе данных!");
mysql_query("set character_set_client='utf8'");
mysql_query("set character_set_results='utf8'");
mysql_query("set collation_connection='utf8_general_ci'");

include_once($_SERVER['DOCUMENT_ROOT'].'/func/func_parse.php');

function curl_parse($url, $post = null, $parse = true){
    $ch = curl_init();
    $agent = $_SERVER["HTTP_USER_AGENT"];
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($post != null){
        curl_setopt($ch, CURLOPT_POST, 1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);        
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/cookie.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/cookie.txt');
    $postResult = curl_exec($ch);
    if ($parse == true){
        return str_get_html(iconv('Windows-1251', 'UTF-8', $postResult)); 
    } else {
        return iconv('Windows-1251', 'UTF-8', $postResult); 
    }  
}

$page = $_GET['page'];

$url = 'https://postupi.online/ssuzy/?page_num='.$page;
$html = curl_parse($url);
$DATA = array();

foreach ($html->find('.list') as $list){
    
    $price = str_replace(array('от ', '&nbsp;'), '', current(explode('/', strip_tags($list->find('.list__price', 0)->innertext))));
    $link = $list->find('h2.list__h a', 0)->href;
    
    $citytype = explode('</span>', $list->find('p.list__pre', 0)->innertext);
    $city = trim(strip_tags($citytype[0]));
    $type = trim(strip_tags($citytype[1]));
    
    // определяем баллы
    $balMin = 0;
    $places_pay = 0; 
    $places_free = 0;
    foreach ($list->find('p.list__score') as $listScore){
        if (preg_match('/бал./', $listScore->innertext)){
            $balMin = trim(str_replace('от', '', strip_tags($listScore->find('span.list__score-sm', 0)->innertext)));    
        }
        if (preg_match('/мест бюджет/', $listScore->innertext)){
            $places_free = trim(strip_tags($listScore->find('b', 0)->innertext));    
        }
        if (preg_match('/мест платно/', $listScore->innertext)){
            $places_pay = trim(strip_tags($listScore->find('b', 0)->innertext));     
        }
    }
    
    $DATA[] = $price;
    $DATA[] = $link;
    $DATA[] = $city;
    $DATA[] = $type;
    $DATA[] = 'bal: '.$balMin;
    $DATA[] = 'pay: '.$places_pay;
    $DATA[] = 'free: '.$places_free;
    
    if (!empty($link)){
        db("INSERT INTO parser_vuz (link, price, city, type, min_score, places_pay, places_free) VALUES ('".$link."', '".$price."', '".$city."', '".$type."', '".$balMin."', '".$places_pay."', '".$places_free."');", "insert");
    }   
}
/*
echo '<pre>';
print_r($DATA);
echo '</pre>';
*/

$page++;

if ($page <= 60){
    header("Refresh: 3; url=/hack_parser_vuz.php?page=".$page);
}