<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/liba/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/liba/functions.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/liba/phpQuery.php";

$arr = array(
  'angarsk' => 'Ангарск',
                                    'astrakhan' => 'Астрахань',
                                    'barnaul' => 'Барнаул',
                                    'belgorod' => 'Белгород',
                                    'bryansk' => 'Брянск',
                                    'velikiy-novgorod' => 'Великий Новгород',
                                    'vladivostok' => 'Владивосток',
                                    'vladikavkaz' => 'Владикавказ',
                                    'vladimir' => 'Владимир',
                                    'volgograd' => 'Волгоград',
                                    'voronezh' => 'Воронеж',
                                    'dimitrovgrad' => 'Димитровград',
                                    'yekaterinburg' => 'Екатеринбург',
                                    'ivanovo' => 'Иваново',
                                    'izhevsk' => 'Ижевск',
                                    'irkutsk' => 'Иркутск',
                                    'kazan' => 'Казань',
                                    'kaliningrad' => 'Калининград',
                                    'kaluga' => 'Калуга',
                                    'kemerovo' => 'Кемерово',
                                    'kirov' => 'Киров',
                                    'krasnodar' => 'Краснодар',
                                    'krasnoyarsk' => 'Красноярск',
                                    'kurgan' => 'Курган',
                                    'kursk' => 'Курск',
                                    'lipetsk' => 'Липецк',
                                    'magnitogorsk' => 'Магнитогорск',
                                    'makhachkala' => 'Махачкала',
                                    'moscow' => 'Москва и Подмосковье',
                                    'murmansk' => 'Мурманск',
                                    'naberezhnye-chelny' => 'Набережные Челны',
                                    'nizhny-novgorod' => 'Нижний Новгород',
                                    'novokuznetsk' => 'Новокузнецк',
                                    'novomoskovsk' => 'Новомосковск',
                                    'novosibirsk' => 'Новосибирск',
                                    'omsk' => 'Омск',
                                    'online' => 'Онлайн',
                                    'orel' => 'Орёл',
                                    'orenburg' => 'Оренбург',
                                    'penza' => 'Пенза',
                                    'perm' => 'Пермь',
                                    'petrozavodsk' => 'Петрозаводск',
                                    'petropavlovsk-kamchatskii' => 'Петропавловск-Камчатский',
                                    'rostov-na-donu' => 'Ростов-на-Дону',
                                    'ryazan' => 'Рязань',
                                    'samara' => 'Самара',
                                    'saint-petersburg' => 'Санкт-Петербург',
                                    'saransk' => 'Саранск',
                                    'saratov' => 'Саратов',
                                    'sevastopol' => 'Севастополь',
                                    'simferopol' => 'Симферополь',
                                    'smolensk' => 'Смоленск',
                                    'sochi' => 'Сочи',
                                    'stavropol' => 'Ставрополь',
                                    'sterlitamak' => 'Стерлитамак',
                                    'surgut' => 'Сургут',
                                    'tver' => 'Тверь',
                                    'tolyatti' => 'Тольятти',
                                    'tomsk' => 'Томск',
                                    'tula' => 'Тула',
                                    'tyumen' => 'Тюмень',
                                    'ulan-ude' => 'Улан-Удэ',
                                    'ulyanovsk' => 'Ульяновск',
                                    'ufa' => 'Уфа',
                                    'khabarovsk' => 'Хабаровск',
                                    'cheboksary' => 'Чебоксары',
                                    'chelyabinsk' => 'Челябинск',
                                    'cherepovets' => 'Череповец',
                                    'chita' => 'Чита',
                                    'yaroslavl' => 'Ярославль'

);

/*
$a = db_query("SELECT * FROM edu_children GROUP BY org_id");

foreach($a as $b) {
    db_query("UPDATE edu_org 
    SET city='".$b['city']."' 
    WHERE uniq_id='".$b['org_id']."' 
    LIMIT 1","u");
}
*/

/*
$a = db_query("SELECT * FROM edu_org");

foreach($a as $b) {
    $uniq_id = mt_rand(1,10000000).time();
    
    db_query("UPDATE edu_org 
    SET uniq_id='".$uniq_id."' 
    WHERE id='".$b['id']."' 
    LIMIT 1","u");
}
*/

/*
$uniq = array();

$a = db_query("SELECT * FROM edu_org");

foreach($a as $b) {
    $uniq[ $b['phone'] ] = $b['uniq_id'];
}


$list = db_query("SELECT * FROM edu_children WHERE org_id=''");

foreach($list as $b) {
    
    db_query("UPDATE edu_children 
    SET org_id='".$uniq[ $b['org_phone'] ]."' 
    WHERE id='".$b['id']."' 
    LIMIT 1","u");
}
*/


// достаём учреждения в отдельную таблицу
/*
$a = db_query("SELECT * FROM edu_children GROUP BY org_phone");

$list = array();
$count = array();

foreach($a as $b) {
    $add = db_query("INSERT INTO edu_org(
    name,
    phone,
    address,
    site,
    url
    ) VALUES (
    '".clearData($b['org_name'])."',
    '".$b['org_phone']."',
    '".clearData($b['org_address'])."',
    '".$b['org_site']."',
    '".$b['url']."'
    )","i");
    
    if (intval($add) == 0)
      echo $b['org_name'].'<br>';
}
*/

/*
$titles = array(
  'Адрес' => 'address',
  'Телефон' => 'phone',
  'Место' => 'place',
  'Описание' => 'descr',
  'Расписание' => 'schedule'
);

$titles2 = array(
  'Цена' => 'price',
  'Возраст' => 'age',
  'Пол' => 'gender'
);

$a = db_query("SELECT * FROM edu_org WHERE phone1=''");

foreach($a as $b) {
    $page = str_replace('https://posleurokov.ru/','',$b['url']);
    $page = str_replace('/','-',$page).'.html';
    
    $html = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/cron/del/'.$page);
    
    $pq = phpQuery::newDocument($html);
        
        // основная информация
        $mainInfoBlock = $pq->find('div.ads-details-info');
        
        $list = explode('<div class="ads-details-info',$mainInfoBlock);
        
        foreach($list as $k=>$v) {
            $v = trim($v);
            
                
            // контакты
            if (preg_match('/h4/',$v)) {
                
               $curr['org_site'] = null;
              $pq = phpQuery::newDocument($v);
              
              $listContacts = $pq->find('ul')->html();
              $listContacts = str_replace('<!--li>','<li>',$listContacts);
              $listContacts = str_replace('</li-->','</li>',$listContacts);
            
              $listCon = explode('</li>',$listContacts);
              
              foreach($listCon as $ltk=>$ltv) {
                
                if (!preg_match('/Email/',$ltv)) {
                    
                    foreach($titles as $name=>$sysName) {
                      if (preg_match('/'.$name.'/',$ltv)) {
                                
                        if ($sysName == 'phone') {
                          $ltv = strip_tags($ltv);

                          $phone = trim( str_replace('Телефон:','',$ltv) );
                          
                          db_query("UPDATE edu_org 
                          SET phone1='".$phone."' 
                          WHERE id=".$b['id']." 
                          LIMIT 1","u");
                          
                        }
                         
                      }
                    }
                }
              }
              
              
              
            }
            // ----------------------------------------------------------------
        }
        // ------------------------------------------------------------------------------------
    
}
*/

/*
foreach($arr as $key=>$val) {
    $a = file_get_contents('https://posleurokov.ru/'.$key.'/all');
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/cron/files2/".$key.".html",$a);
}
*/



/*
$arr3 = array();

foreach($arr as $key=>$val) {
    $html = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/cron/files2/'.$key.'.html');
    $pq = phpQuery::newDocument($html);
    
    $list = $pq->find('ul.pagination');
    $pq = phpQuery::newDocument($list);
    
    $list2 = $pq->find('a');
    
    $list3 = explode('<a href="',$list2);
    
    $arr2 = array();
    
    foreach($list3 as $k=>$v) {
        if ($k!=0) {
            preg_match_all('/data-ci-pagination-page=".*"/',$v,$rt);
            $num = clearData($rt[0][0],'phone');
            $arr2[ $num ] = $num;
        }
    }
    
    $maxNum = max($arr2);
    $arr3[ $key ] = $maxNum;
}

foreach($arr3 as $city=>$maxPage) {
    if (!empty($maxPage)) {
        for($i=10;$i<=$maxPage;$i+=10) {
            $a = file_get_contents('https://posleurokov.ru/'.$city.'/all/'.$i);
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/cron/files2/".$city.$i.".html",$a);
        }
    }
}
*/
/*
$a = scandir($_SERVER['DOCUMENT_ROOT'].'/cron/files2');

foreach($a as $key=>$val) {
    if ($val!='.' && $val!='..') {
        $html = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/cron/files2/'.$val);
        $pq = phpQuery::newDocument($html);
        
        $list = $pq->find('a[itemprop="url"]');
        
        $list2 = explode('<a',$list);
        
        foreach($list2 as $k=>$v) {
            $v = trim($v);
            if (!empty($v)) {
                $pq = phpQuery::newDocument('<a '.$v);
                $link = $pq->find('a')->attr('href');
                
                if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cron/links.txt'))
                  file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cron/links.txt', $link."\r\n", FILE_APPEND);
                  
                else
                  file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cron/links.txt', $link."\r\n");
                  
            }
        }
        
        //exit();
        unlink($_SERVER['DOCUMENT_ROOT'].'/cron/files2/'.$val);
    }
}
*/

/*
$a = file($_SERVER['DOCUMENT_ROOT'].'/cron/links.txt');

foreach($a as $key=>$val) {
    $val = trim($val);
    $html = file_get_contents($val);
    
    $n = str_replace('https://posleurokov.ru/','',$val);
    $n = str_replace('/','-',$n);
    
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/cron/files2/".$n.".html",$html);
    
    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cron/links2.txt'))
      file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cron/links2.txt', $val."\r\n", FILE_APPEND);
                  
    else
      file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cron/links2.txt', $val."\r\n");
}
*/


/*
$titles = array(
  'Адрес' => 'address',
  'Телефон' => 'phone',
  'Место' => 'place',
  'Описание' => 'descr',
  'Расписание' => 'schedule'
);

$titles2 = array(
  'Цена' => 'price',
  'Возраст' => 'age',
  'Пол' => 'gender'
);


$i = 1;
$a = scandir($_SERVER['DOCUMENT_ROOT'].'/cron/files3');

foreach($a as $key=>$val) {
    
    $curr = array();
    
    if ($val!='.' && $val!='..') {
        
        $html = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/cron/files3/'.$val);
        
        $pq = phpQuery::newDocument($html);
        
        // основная информация
        $mainInfoBlock = $pq->find('div.ads-details-info');
        
        $list = explode('<div class="ads-details-info',$mainInfoBlock);
        
        foreach($list as $k=>$v) {
            $v = trim($v);
            
            if (!empty($v)) {
                
                
                $blocks = explode('<h5 class="list-title">',$v);
                
                foreach($blocks as $t=>$value) {
                    if ($t!=0) {
                        
                        foreach($titles as $name=>$sysName) {
                            if (preg_match('/'.$name.'/',$value)) {
                                
                                if ($sysName == 'address') {
                                    $pq = phpQuery::newDocument($value);
                                    $address = $pq->find('p')->text();
                                    $address = trim( str_replace('(на карте)','',$address) );
                                    
                                    $curr[$sysName] = $address;
                                } 
                                
                                if ($sysName == 'place' || $sysName == 'descr' || $sysName == 'schedule') {
                                    $pq = phpQuery::newDocument($value);
                                    $place = $pq->find('p')->text();
                                    $place = trim( $place );
                                    
                                    $curr[$sysName] = $place;
                                }
                                
                                if ($sysName == 'phone') {
                                    $pq = phpQuery::newDocument($value);
                                    $phoneList = $pq->find('div');
                                    
                                    $lp = explode('<div>',$phoneList);
                                    
                                    foreach($lp as $pk=>$pv) {
                                        $pv = clearData($pv,'phone');
                                        if (!empty($pv)) {
                                            $phone = $pv;
                                            $curr[$sysName][] = $phone;
                                        }
                                    }
                                } 
                            }
                        }
                    }
                }
            }
            
            // контакты
            if (preg_match('/h4/',$value)) {
                
               $curr['org_site'] = null;
              $pq = phpQuery::newDocument($value);
              $orgName = $pq->find('h4')->text(); // имя организатора или название учреждения
              
              $curr['org_name'] = $orgName;
              
              $listContacts = $pq->find('ul')->html();
              $listContacts = str_replace('<!--li>','<li>',$listContacts);
              $listContacts = str_replace('</li-->','</li>',$listContacts);
              
              
              $listCon = explode('</li>',$listContacts);
              
              foreach($listCon as $ltk=>$ltv) {
                
                if (!preg_match('/Email/',$ltv)) {
                    
                    foreach($titles as $name=>$sysName) {
                      if (preg_match('/'.$name.'/',$ltv)) {
                                
                        if ($sysName == 'address') {
                          $ltv = strip_tags($ltv);
                          $address = trim(str_replace($name.':','',$ltv));        
                          $curr['org_address'] = $address;
                        } 
                                
                        if ($sysName == 'phone') {
                          $ltv = strip_tags($ltv);
                          $phone = clearData($ltv,'phone');
                          $curr['org_phone'] = $phone;
                        }
                         
                      }
                    }
                    
                    if (preg_match('/http/',$ltv)) {
                        $ltv = strip_tags($ltv);
                        $site = trim($ltv);
                        $curr['org_site'] = $site;
                    }    
                    
                }
              }
              
              
             // exit();
            }
            // ----------------------------------------------------------------
        }
        // ------------------------------------------------------------------------------------
        
        // цена, возраст, пол
        $pq = phpQuery::newDocument($html);
        $dopInfoBlock = $pq->find('aside.panel-details');
        
        $pq = phpQuery::newDocument($dopInfoBlock);
        
        $lt = $pq->find('p')->html();
        
        $ltArr = explode('<strong>',$lt);
        
        foreach($ltArr as $lk=>$lv) {
            foreach($titles2 as $name=>$sysName) {
               if (preg_match('/'.$name.'/',$lv)) {
                  if ($sysName == 'price' || $sysName == 'age' || $sysName == 'gender') {
                     $text = str_replace($name.':</strong>','',$lv);
                     $text = str_replace('–','-',$text);
               
                     $text = trim($text);
                     $curr[$sysName] = $text;
                  } 
               }
            }
        }
        // ------------------------------------------------------------------------------------
        
        // заголовок
        $pq = phpQuery::newDocument($html);
        
        $title = $pq->find('h1')->text();
        $title = trim($title);
        
        $curr['title'] = $title;
        // ------------------------------------------------------------------------------------
        
        
        $pagename = str_replace('.html','',$val);
        $pagename = str_replace('-','/',$pagename);
        $url = 'https://posleurokov.ru/'.$pagename;
        
        $cityArr = explode('-',$val);
        $city = $arr[ $cityArr[0] ];
        
        $curr['url'] = $url;
        $curr['city'] = $city;
        
        $phone1 = null;
        $phone2 = null;
        $phone3 = null;
        
        if (!empty($curr['phone'][0]))
          $phone1 = $curr['phone'][0];
          
        if (!empty($curr['phone'][1]))
          $phone2 = $curr['phone'][1];
          
        if (!empty($curr['phone'][2]))
          $phone3 = $curr['phone'][2];
        
        $add = db_query("INSERT INTO edu_children (
        name,
        address,
        place,
        phone1,
        phone2,
        phone3,
        description,
        schedule,
        price,
        age,
        gender,
        city,
        url,
        org_name,
        org_phone,
        org_address,
        org_site
        ) VALUES (
        '".clearData($curr['title'])."',
        '".clearData($curr['address'])."',
        '".clearData($curr['place'])."',
        '".$phone1."',
        '".$phone2."',
        '".$phone3."',
        '".clearData($curr['descr'])."',
        '".clearData($curr['schedule'])."',
        '".clearData($curr['price'])."',
        '".clearData($curr['age'])."',
        '".clearData($curr['gender'])."',
        '".$curr['city']."',
        '".$curr['url']."',
        '".clearData($curr['org_name'])."',
        '".$curr['org_phone']."',
        '".clearData($curr['org_address'])."',
        '".$curr['org_site']."'
        )","i");
        
        if (intval($add) > 0) {
            if ( copy($_SERVER['DOCUMENT_ROOT'].'/cron/files3/'.$val, $_SERVER['DOCUMENT_ROOT'].'/cron/del/'.$val) ) {
                unlink($_SERVER['DOCUMENT_ROOT'].'/cron/files3/'.$val);
            }
        }
        
        else {
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/cron/errors/".$i.".txt",$curr['title'].' - '.$curr['url']);
        }
        
        $i++;
        
    }
}

*/