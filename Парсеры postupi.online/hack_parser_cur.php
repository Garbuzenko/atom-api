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

$a = db("SELECT * FROM parser_vuz WHERE status_curr=0 ORDER BY id ASC LIMIT 1;", "one");

if ($a != false){
    
    db("UPDATE parser_vuz SET status_curr=1 WHERE id='".$a['id']."' LIMIT 1;");
    
    if (preg_match('/\/ssuz\//', $a['link'])){
        $vs = 2;    
    } else {
        $vs = 1;
    }
    
    if ($vs == 1){
        $urls = array($a['link'].'programmy-obucheniya/bakalavr/', $a['link'].'programmy-obucheniya/magistratura/');    
    } else {
        $urls = array($a['link'].'programmy-obucheniya/'); 
    }
    
    $url_type = 1;
    
    foreach ($urls as $url){
        
        $html = curl_parse($url);
        $DATA = array();
        
        // проверяем наличие страниц
        $pages = array(1);
        foreach ($html->find('div.invite.fetcher a.paginator') as $apag){
            $pages[] = $apag->innertext;
        }
        
        foreach ($pages as $page){
            
            $DATA = array();
            
            if ($page > 1){
                $html = curl_parse($url.'?page_num='.$page);   
            }
            
            foreach ($html->find('.list') as $list){
                
                $DATA = array();
                
                $price = str_replace(array('от ', '&nbsp;'), '', current(explode('/', strip_tags($list->find('.list__price', 0)->innertext))));
                $link = $list->find('h2.list__h a', 0)->href;
                
                //$citytype = explode('</span>', $list->find('p.list__pre', 0)->innertext);
                //$city = trim(strip_tags($citytype[0]));
                //$type = trim(strip_tags($citytype[1]));
                
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
                $DATA[] = 'bal: '.$balMin;
                $DATA[] = 'pay: '.$places_pay;
                $DATA[] = 'free: '.$places_free;
                $DATA[] = 'source: '.$a['link'];
                
                if (!empty($link)){
                    db("INSERT INTO parser_curr (link, price, min_score, places_pay, places_free, att_type, source_url) VALUES ('".$link."', '".$price."', '".$balMin."', '".$places_pay."', '".$places_free."', '".$url_type."', '".$a['link']."');", "insert");
                } 
                   
            }
        
        }
        $url_type++;
    }

    header("Refresh: 1; url=/hack_parser_cur.php");

    echo '<pre>';
    //print_r($DATA);
    print_r($urls);
    print_r($pages);
    echo '</pre>';

       

}
