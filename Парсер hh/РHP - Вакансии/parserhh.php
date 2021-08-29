<?
//ini_set("display_errors","1");
//error_reporting(E_ALL);


function db($query, $return = 'custom', $format = true){

    global $CONFIG;

    $q = mysql_query($query);

    if ($return !== 'delete' and $return !== 'insert' and $return !== 'update'){

        $Array = array();
        $i = 0;

        if ($return == 'custom' or $return == 'count' or $return == 'found_rows'){
           	while ($d = mysql_fetch_assoc($q)){
       			$Array[$d['id']] = array();
          		foreach($d as $key => $val){
                    $Array[$d['id']][$key] = $val;
          		}
                $i++;
            }

        } elseif ($return == 'one'){
            $Array = mysql_fetch_assoc($q);
            $i++;

        }

        if ($return == 'count'){
            return $i;
        } else {
            if ($i > 0 or $return == 'found_rows'){
                if ($return == 'found_rows') {

                    $result = mysql_query("SELECT FOUND_ROWS()");
                    $count = mysql_result($result, 0);
                    if (empty($count)){
                        $count = 0;
                    }

                    return array(
                        'array' => $Array,
                        'found_rows' => $count
                    );

                } else {
                    return $Array;
                }
            } else {
                return false;
            }
        }

    } elseif ($return == 'insert'){

        return mysql_insert_id();

    } else {

        return $q;

    }
}

/////////////////////////////////////////////////////
/////////////////////////////////////////////////////

$CONFIG = array(
	// данные соединения с MySQL
	'DB_HOST' => 'localhost',
	'DB_BASE' => 'goodlijy_test',
	'DB_USER' => 'goodlijy_test',
	'DB_PASS' => 'qneZK*N8'
);

mysql_connect($CONFIG['DB_HOST'], $CONFIG['DB_USER'], $CONFIG['DB_PASS']) or die("Ошибка подключения к базе данных!");
mysql_select_db($CONFIG['DB_BASE']) or die("Нет доступа к базе данных!");
mysql_query("set character_set_client='utf8'");
mysql_query("set character_set_results='utf8'");
mysql_query("set collation_connection='utf8_general_ci'");


if ($_GET['m'] == 'genUrl'){

    $reg = db("SELECT * FROM hh_region;");
    $spec = db("SELECT * FROM hh_specialization;");

    foreach($spec as $k => $v){
        foreach($reg as $k2 => $v2){

            $link = 'https://perm.hh.ru/search/resume?area='.$v2['hh_id'].'&relocation=living_or_relocation&specialization='.$v['hh_id'];
            db("INSERT INTO links (link, region_id, specialization_id) VALUES ('".$link."','".$v2['hh_id']."','".$v['hh_id']."');");

        }
    }

}




if ($_GET['m'] == 'getUrl'){
    $db = db("SELECT * FROM links WHERE status=0 ORDER BY `specialization_id` ASC LIMIT 1;", "one");
    if ($db != false){
        db("UPDATE links SET status=1 WHERE id='".$db['id']."' LIMIT 1;");
        echo $db['link'];
    } else {
        echo 'NULL';
    }
}


if ($_GET['m'] == 'parseTake'){

    $col = $_GET['col'];
    db("UPDATE links SET col='".$col."', status=2 WHERE status='1' LIMIT 1;");

}

?>