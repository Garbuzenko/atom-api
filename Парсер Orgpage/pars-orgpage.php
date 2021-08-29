<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/liba/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/liba/functions.php";
exit();

function repeatDownload($num) {
    if (empty($num))
      return false;
      
    $url = 'https://www.orgpage.ru/Rubricator/ajax/RubricatorRegionLevel2Next/?rubricId=39&rubricLevel=3&excludeIdList=0&countryId=1&offsetNum='.$num;
    $resp = file_get_contents($url);

    $json = str_replace('\r\n','',$resp);
    
    if (!empty($json)) {
        return $json;
    }
    
    return false;
    
}

$n=1;

$a = file($_SERVER['DOCUMENT_ROOT'].'/cron/child.txt');

foreach($a as $key=>$val) {
    
    $val = trim($val);
    $html = file_get_contents($val);
    
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/cron/files4/".$n.".html",$html);
    
    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cron/child2.txt'))
      file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cron/child2.txt', $val."\r\n", FILE_APPEND);
                  
    else
      file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cron/child2.txt', $val."\r\n");
      
    $n++;
}

/*
$pages = 2056;

for ($i=1;$i<=$pages;$i++) {
    
    $url = 'https://www.orgpage.ru/Rubricator/ajax/RubricatorRegionLevel2Next/?rubricId=39&rubricLevel=3&excludeIdList=0&countryId=1&offsetNum='.$i;
    $resp = file_get_contents($url);

    $json = str_replace('\r\n','',$resp);
    
    if (!empty($json)) {
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cron/files3/child'.$i.'.txt',$json);
    }
    
    else {
        $json = repeatDownload($i);
        
        if (!empty($json)) {
            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cron/files3/child'.$i.'.txt',$json);
        }
        
        else {
            if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cron/data.txt'))
               file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cron/data.txt', $i."\r\n", FILE_APPEND);
                  
            else
              file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cron/data.txt', $i."\r\n");
        }
    }
    
}
*/