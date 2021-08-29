<?php
//header('Content-Type: text/html; charset=utf-8'); // устанавливаем кодировку

require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/liba/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/liba/functions.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/liba/phpQuery.php";

$list = scandir($_SERVER['DOCUMENT_ROOT'] . '/cron/files4');

foreach ($list as $k => $v) {

    if ($v != '.' && $v != '..') {
        
        $arr = array();
        
        $html = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/cron/files4/'.$v);
        
        if (!empty($html)) {
            $pq = phpQuery::newDocument($html);

            $name = $pq->find('h1[itemprop="name"]')->text();
            $name = trim($name);
            $arr['name'] = $name; // название
            
            // телефоны
            $ph = $pq->find('span[itemprop="telephone"]')->text();
            
            $ph1 = explode('+',$ph);
            
            foreach($ph1 as $key=>$val) {
                if (!empty($val)) {
                    $arr['phone'][] = '+'.$val;
                }
            }
            // ----------------------------------------------------
            
            // сайт
            $site = $pq->find('link[itemprop="url"]')->attr('href');
            $arr['site'] = $site;
            // ----------------------------------------------------
            
            // email
            $email = $pq->find('a[itemprop="email"]')->text();
            $arr['email'] = preg_replace('/\s/','',$email);
            // ----------------------------------------------------
            
            // город (регион) и адрес
            $address = $pq->find('span[itemprop="streetAddress"]')->text();
            
            $reg = $pq->find('div.company-information__address-text')->text();
            $reg = preg_replace('/\s+/',' ',$reg);
            //$reg = str_replace(',',', ',$reg);
            
            $arr['address'] = $address.' ('.$reg.')';
            // ----------------------------------------------------
            
            // часы работы
            $sched = $pq->find('div.period')->html();
            $sched = preg_replace('/\s/','',$sched);
            $sched = str_replace('</p><p>',' ',$sched);
            $sched = strip_tags($sched);
            $arr['schedule'] = $sched;
            // ----------------------------------------------------
            
            // описание
            $descr = $pq->find('div.company-about__text')->text();
            $descr = trim($descr);
            // ----------------------------------------------------
            
            // доп описание
            $descr2 = $pq->find('div.company-product__text')->html();
            $descr2 = str_replace('<br>',"\r\n", trim($descr2));
            
            $arr['description'] = $descr."\r\n ".$descr2;
            // ----------------------------------------------------
            
            $phone1 = null;
            $phone2 = null;
            $phone3 = null;
            
            if (!empty($arr['phone'][0])) {
                $phone1 = $arr['phone'][0];
            }
            
            if (!empty($arr['phone'][1])) {
                $phone2 = $arr['phone'][1];
            }
            
            if (!empty($arr['phone'][2])) {
                $phone3 = $arr['phone'][2];
            }
            
            $add = db_query("INSERT INTO edu_company(
           	name,
            description,
            schedule,
            type,
           	source_id,
           	phone1,
           	phone2,
           	phone3,
            site,
            email,
            address,
            city
            ) VALUES (
            '".clearData($arr['name'])."',
            '".clearData($arr['description'])."',
            '".clearData($arr['schedule'])."',
            2,
            7,
            '".$phone1."',
            '".$phone2."',
            '".$phone3."',
            '".$arr['site']."',
            '".$arr['email']."',
            '".clearData($arr['address'])."',
            '".$reg."'
            )","i");
            
            if (intval($add) > 0) {
               if (copy($_SERVER['DOCUMENT_ROOT'] . '/cron/files4/'.$v, $_SERVER['DOCUMENT_ROOT'] .'/cron/del/'.$v)) {
                 unlink($_SERVER['DOCUMENT_ROOT'] . '/cron/files4/'.$v);
               }
            }
            
            
        }
        
        //exit( print_r($arr) );
    }
}