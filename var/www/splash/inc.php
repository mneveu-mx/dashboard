<?php

function curl_download($Url){
    // is cURL installed yet?
    if (!function_exists('curl_init')){
        die('Sorry cURL is not installed!');
    }
    // OK cool - then let's create a new cURL resource handle
    $ch = curl_init();
    // Now set some options (most are optional)
    // Set URL to download
    curl_setopt($ch, CURLOPT_URL, $Url);
    // Set a referer
    curl_setopt($ch, CURLOPT_REFERER, "http://www.example.org/yay.htm");
    // User agent
    curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
    // Include header in result? (0 = yes, 1 = no)
    curl_setopt($ch, CURLOPT_HEADER, 0);
    // Should cURL return or print out the data? (true = return, false = print)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Timeout in seconds
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    // Download the given URL, and return output
    $output = curl_exec($ch);
    // Close the cURL resource, and free system resources
    curl_close($ch);
    return $output;
}

function getAllLights(){
	global $lights;
	$url='http://192.168.1.10/api/FHZToMU3yj6k7OPkexAZRPuS2qXtfr-AsoWCghq9/lights';
	$json=curl_download($url);
	$lights=json_decode($json, true);
}

function getAllSensors(){
        global $sensors;
        $url='http://192.168.1.10/api/FHZToMU3yj6k7OPkexAZRPuS2qXtfr-AsoWCghq9/sensors';
        $json=curl_download($url);
        $sensors=json_decode($json, true);
}

function getIndex($name, $array){
    foreach($array as $key => $value){
        if(is_array($value) && $value['name'] == $name)
              return $key;
    }
    return null;
}
function RGBFromXYBri($x, $y, $brightness) {
        $Y = $brightness;
        $X = ($Y / $y) * $x;
        $Z = ($Y / $y) * (1 - $x - $y);
        $rgb = [
            $X * 1.612 - $Y * 0.203 - $Z * 0.302,
            -$X * 0.509 + $Y * 1.412 + $Z * 0.066,
            $X * 0.026 - $Y * 0.072 + $Z * 0.962
        ];

        $rgb = array_map(function ($x) {
            return ($x <= 0.0031308) ? (12.92 * $x) : ((1.0 + 0.055) * pow($x, (1.0 / 2.4)) - 0.055);
        }, $rgb);

        $rgb = array_map(function ($x) { return max(0, $x); }, $rgb);
        $max = max($rgb[0], $rgb[1], $rgb[2]);
        if ($max > 1)
            $rgb = array_map(function ($x) use($max) { return $x / $max; }, $rgb);

        $rgb = array_map(function ($x) { return $x * 255;}, $rgb);
        return [
            'r' => $rgb[0],
            'g' => $rgb[1],
            'b' => $rgb[2]
        ];
}
function gethuecolor($pattern, $icon){
        global $lights;
        getAllLights();
        $id=getIndex($pattern,$lights);

        $reachable=$lights[$id]["state"]["reachable"];
        $on=$lights[$id]["state"]["on"];
        $xy=$lights[$id]["state"]["xy"];
        $bri=$lights[$id]["state"]["bri"];
        $rgb=RGBFromXYBri($xy[0],$xy[1],$bri);

        $html='';
        if($on && $reachable){
		$color = sprintf("#%02x%02x%02x",$rgb["r"] ,$rgb["g"],$rgb["b"]);
                $html.='<div class="hue_color" style="background-color:'.$color.';">';
                $html.='<img src="img/lamp_hue.png" style="margin-top:10px;margin-left:15px;"/><img src="'.$icon.'" style="margin-top:10px;margin-left:20px;"/></div>';
        }else if(!$on && $reachable){
                $html.='<div class="hue_reachable"><img src="'.$icon.'" style="margin-top:30px;margin-left:42px;"/></div>';
        }else{
                $html.='<img src="'.$icon.'_offline" style="margin-top:31px;margin-left:1px;"/>';
        }
        return $html;
}
function gethuesdb(){
	global $lights;
	global $sensors;
	$history_sensorLastDetected=$total_pages = apc_fetch('temp_sdb');
	$html=gethuecolor('HUE_SDB','img/hue_sdb.png');
	getAllSensors();
	$idLight=getIndex('HUE_SDB',$lights);
	$idSensor=getIndex('Mouvement_SDB', $sensors);
 	$lightReachable=$lights[$idLight]["state"]["reachable"];
        $lightOn=$lights[$idLight]["state"]["on"];
	$sensorReachable=$sensors[$idSensor]["config"]["reachable"];
	$sensorOn=$sensors[$idSensor]["config"]["on"];
	$sensorLastDetected_gmt=$sensors[$idSensor]["state"]["lastupdated"];
	$sensorLastDetected_date=new DateTime($sensorLastDetected_gmt);
	$sensorLastDetected=$sensorLastDetected_date->getTimestamp();
	$diff=$sensorLastDetected-$history_sensorLastDetected;
	//DEBUG $html.=$history_sensorLastDetected."/".$sensorLastDetected."#".$diff;
	if($sensorReachable && $sensorOn && $lightReachable)
	if(($sensorLastDetected-$history_sensorLastDetected)>20){
		/* Nouvelle détection */
		if($lightOn){ //La lumière est allumée => il faut l'éteindre
			sendStateToLight($idLight,false);	
		}else{ //La lumière est éteinte => il faut l'allumer
			sendStateToLight($idLight,true);
		}
		apc_store('temp_sdb', $sensorLastDetected);
	}else if(($sensorLastDetected-$history_sensorLastDetected)>0){
		apc_store('temp_sdb', $sensorLastDetected);
	}

	return $html;
}

function sendStateToLight($idLight, $state){
$data = array('on'=>$state);
$data_json = json_encode($data);

$ch = curl_init();
$url="http://192.168.1.10/api/FHZToMU3yj6k7OPkexAZRPuS2qXtfr-AsoWCghq9/lights/".$idLight."/state";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);
}

function gethuesalon(){
	return gethuecolor('HUE_Salle','img/hue_salon.png');
}

function ping($host){
	exec(sprintf('ping -c 1 -W 5 %s', escapeshellarg($host)), $res, $rval);
	return $rval === 0;
}

function checkService($ip, $icon){
    $availability = ping($ip);
    $html = '';
    $html .= $availability ? '<div class="ping_ok">' : '<div class="ping_nok">';
    $html .= '<img src="'.$icon.'" style="margin-top:10px;"/></div>';
    $html .= '</div>';
    return $html;
}
function checkhue(){
    return checkService('192.168.1.10', 'img/hue_logo.png');
}

function checkchromecast(){
    return checkService('192.168.1.17', 'img/chromecast.png');
}


/////////////////////////////////////////////////
//  Meteo
/////////////////////////////////////////////////

function meteo () {
  $meteo = '<!-- widget meteo -->

<div id="cont_NTkzNTB8NHwzfDF8M3xGOEEwQzJ8MnxGRkZGRkZ8Y3wx">
<div id="spa_NTkzNTB8NHwzfDF8M3xGOEEwQzJ8MnxGRkZGRkZ8Y3wx">

</div>
<script type="text/javascript" src="meteo.js"></script>
</div>

<!-- widget meteo -->';
  return $meteo;
}

function rss () {
	
    $url = 'http://www.lemonde.fr/rss/une.xml';	// L'adresse du flux rss
    $nb_items_a_afficher = 9;					// Le nb d'articles à afficher
    $domain = parse_url($url, PHP_URL_HOST);	// Extraction du nom de domaine du flux rss
    if($domain{3} == ".") {						// Suppression du www. si besoin
    	$domain = substr($domain, 4);
    }
    $domain = substr($domain, 0, strpos($domain, '.'));	// Suppression du .extension
    $logo = $domain.'.png';						// Création du nom du logo à associer au flux rss
    if(!file_exists('./rss/'.$logo)){			// Remplacement par un logo standard si le fichier n'existe pas
    	$logo = 'logo.png';
    }
	
	// Récupération des articles Rss
    $rss = new rss_php;
    $rss->load($url);
    $items = $rss->getItems();
    
    $html = '';
    foreach($items as $index => $item) {
    	if($index == $nb_items_a_afficher) {
    	break;
    	}
    	$html .= '<img src="./rss/'.$logo.'" title="Rss" alt="Rss">';
        $html .= '<a href="'.$item['link'].'" title="'.$item['title'].'">'.$item['title'].'</a>
        <div class="rss_texte">'.strip_tags($item['description']).'</div>';
    }


    return $html;
  }


?>


