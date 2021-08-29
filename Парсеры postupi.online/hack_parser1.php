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

$a = db("SELECT * FROM parser_vuz WHERE status=0 ORDER BY id ASC LIMIT 1;", "one");

if ($a != false){
    
    //echo $a['id'].'<br />';
    
    db("UPDATE parser_vuz SET status=1 WHERE id='".$a['id']."' LIMIT 1;");
    
    $url = $a['link'];
    $html = curl_parse($url);
    
    $DATA = array();
    
    $DATA['title'] = $html->find('h1#prTitle', 0)->innertext;
    $DATA['contacts_link'] = $html->find('a.menu-internal__link.contacts-icon', 0)->href;
        
    $DATA['site'] = $html->find('.info-box-nd .contact-icon.site a', 0)->href;
    $DATA['email'] = $html->find('.info-box-nd .contact-icon.mail a', 0)->innertext;
    $DATA['phone'] = $html->find('.info-box-nd .contact-icon.phone', 0)->innertext;
    $DATA['address'] = $html->find('.info-box-nd .contact-icon.address', 0)->innertext;
    $DATA['count_programs'] = $html->find('.menu-internal__link.program-icon .menu-internal__cnt', 0)->innertext;
    
    $DATA['hostel'] = $html->find('.card-nd-pre', 0)->innertext;
    if (preg_match('/Общежитие/i', $DATA['hostel'])){
        $DATA['hostel'] = 1;    
    } else {
        $DATA['hostel'] = 0;
    }
    
    // парсим лого
    $logoName = '';
    $logoUrl = $html->find('img.bg-nd__logo', 0)->src;
    if (!empty($logoUrl)){
        $logo = file_get_contents($logoUrl); 
        $logo_ext = end(explode('.', $logoUrl));
        $logoName = uniqid('f').'.'.$logo_ext;
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/files/logo/'.$logoName, $logo);
    }
    
    
    // парсим полное описание
    $DATA['id'] = $html->find('input#main_vuz_id', 0)->value;
    $html = curl_parse('https://spb.postupi.online/ajax.php?mode=vuz_max_descr', 'id='.$DATA['id'], false);
    $DATA['descr'] = strip_tags($html);
    
    if (!empty($DATA['title'])){
        
        $id = db("INSERT INTO edu_institution 
        (
        full_name, 
        short_name, 
        type, 
        state_type,
        description,
        count_curricula,
        min_price,
        min_score,
        places_pay,
        places_free,
        hostel,
        avatar,
        city_name,
        source_id,
        source_url
        ) 
        VALUES 
        (
        '".$DATA['title']."', 
        '".$DATA['title']."', 
        '5', 
        '".$a['type']."',
        '".$DATA['descr']."',
        '".$DATA['count_programs']."',
        '".$a['price']."',
        '".$a['min_score']."',
        '".$a['places_pay']."',
        '".$a['places_free']."',
        '".$DATA['hostel']."',
        '".$logoName."',
        '".$a['city']."',
        '2',
        '".$url."'
        );", "insert");
        
        if ($id > 0){
            
            db("INSERT INTO edu_contacts 
            (
            institution_id, 
            type, 
            site, 
            email,
            phone,
            address
            ) 
            VALUES 
            (
            '".$id."', 
            '3', 
            '".$DATA['site']."',
            '".$DATA['email']."',
            '".$DATA['phone']."',
            '".$DATA['address']."'
            );", "insert");
            
        }
        
    }
    
    header("Refresh: 2; url=/hack_parser1.php");
    
    echo '<pre>';
    print_r($DATA);
    echo '</pre>';

}