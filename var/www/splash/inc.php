<?php

function checkhue(){
    exec(sprintf('ping -c 1 -W 5 %s', escapeshellarg('192.168.1.10')), $res, $rval);
    $html = '';
    $html .= $rval === 0 ? '<div class="ping_ok">' : '<div class="ping_nok">';
    $html .= '<img src="img/hue_logo.png" style="margin-top:10px;"/></div>';
    $html .= '</div>';
    return $html;
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


