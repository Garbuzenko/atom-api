<?php

mysql_connect('localhost', 'goodlijy_atom', 'z%LwU0lJ') or die("Ошибка подключения к базе данных!");
mysql_select_db('goodlijy_atom') or die("Нет доступа к базе данных!");
mysql_query("set character_set_client='utf8'");
mysql_query("set character_set_results='utf8'");
mysql_query("set collation_connection='utf8_general_ci'");

include_once($_SERVER['DOCUMENT_ROOT'].'/func/func_parse.php');

//ini_set("display_errors","1");
//error_reporting(E_ALL);

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

$as = db("SELECT * FROM parser_curr WHERE status=0 ORDER BY id ASC LIMIT 0,2;");

if ($as != false){
    
    //echo $a['id'].'<br />';
    
    foreach ($as as $k => $a){
    
    db("UPDATE parser_curr SET status=1 WHERE id='".$a['id']."' LIMIT 1;");
    
    // берем информацию из вуза
    $vuz = db("SELECT * FROM edu_institution WHERE source_url='".$a['source_url']."' LIMIT 1;", "one");
    
    $url = $a['link'];
    $html = curl_parse($url);
    
    $DATA = array();
    
    $DATA['title'] = $html->find('h1#prTitle', 0)->innertext;    
    
    // парсим полное описание
    $DATA['descr'] = strip_tags($html->find('.descr-min', 0)->innertext).strip_tags($html->find('.descr-max', 0)->innertext);
    
    // парсим тэги
    $DATA['tags'] = $html->find('li[itemprop="itemListElement"]', -1)->find('span', 0)->innertext; 

    // направление обучения
    $DATA['direction'] = current(explode(' (', $html->find('.bg-nd__pre a', -1)->innertext));
    
    // экзамены
    $DATA['exam'] = array(); 
    //foreach ($html->find('.score-box', -1)->find('.score-box__item div p') as $exam){
    //    $DATA['exam'][] = str_replace('&nbsp;', '', strip_tags($exam->innertext));  
    //}
    //$DATA['exam'] = implode('||', $DATA['exam']);
    
    $DATA['program_type'] = '';
    // тип программы
    foreach ($html->find('div.detail-box .detail-box__item') as $detailBox){
        if (preg_match('/Уровень образования/', $detailBox->find('.detail-box__h', 0)->innertext)){
            $DATA['program_type'] = trim(strip_tags($detailBox->find('div span', 0)->innertext));    
        }
    }

    
    // варианты обучения
    $DATA['educations'] = array();
    $url = $url.'varianti/';
    $html = curl_parse($url);
    $edu_forms = array();
    
    foreach ($html->find('.list-wrap .list.list-var') as $listCol){
        
        $params = explode(',', strip_tags($listCol->find('.list-var__params', 0)->innertext));
        
        $pay = str_replace(array(' вариант', '&nbsp'), '',$params[0]);
        $type = trim(str_replace(array(' вариант', '&nbsp'), '', $params[1]));
        $long = trim(str_replace(array(' обучения', '&nbsp'), '', $params[2]));
        
        $DATA['educations'][] = array(
            'pay' => $pay,
            'type' => $type,
            'long' => $long
        );
        
        if (in_array($type, $edu_forms) == false){
            $edu_forms[] = $type;    
        }
    }
    
    $DATA['edu_form'] = implode(', ', $edu_forms);
    
    // профессии
    $DATA['result_professions'] = array();
    $url = $url.'professii/';
    $html = curl_parse($url);
    foreach ($html->find('.list-col-wrap .list-col') as $listCol){
        $DATA['result_professions'][] = $listCol->find('.list-col__h a', 0)->innertext;
    }
    $DATA['result_professions'] = implode(', ', $DATA['result_professions']);
    

    if (!empty($DATA['title'])){
        
        $id = db("INSERT INTO edu_curriculum 
        (
        name, 
        is_published, 
        direction_of_study, 
        description,
        budget_places,
        passing_score,
        duration,
        competitions,
        is_admission_exam,
        admissions_exams,
        education_forms,
        institution_id,
        max_price,
       	min_price,
        result_professions,
        type,
        program_type,
        source_id,
        source_url,
        edu_form,
        tags
        ) 
        VALUES 
        (
        '".$DATA['title']."', 
        '1', 
        '".$DATA['direction']."', 
        '".$DATA['descr']."',
        '".$a['places_free']."', 
        '".$a['min_score']."', 
        '".$long."',
        '', 
        '1', 
        '".$DATA['exam']."',
        '".json_encode($DATA['educations'], JSON_UNESCAPED_UNICODE)."', 
        '".$vuz['id']."', 
        '', 
        '".$a['price']."', 
        '".$DATA['result_professions']."', 
        '".$vuz['type']."',
        '".$DATA['program_type']."',
        '2', 
        '".$a['link']."', 
        '".$DATA['edu_form']."',
        '".$DATA['tags']."'
        );", "insert");
        
    }
    
    }
    
    header("Refresh: 0; url=/hack_parser2.php");
    
    echo '<pre>';
    print_r($DATA);
    echo '</pre>';

}