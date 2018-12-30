/* initialisation des fonctions de tous les modules au chargement de la page */
$(document).ready(function() {
   xplanet();
checkhue();
   rss();
   horloge();
   meteo();
   conte();
});

var xplanet_timeout;

function xplanet () {

  var now = new Date().getTime();

  /* préchargement des images */
  var img_earth = $("<img />").attr("src", "img/xplanet_earth.png?"+now);

  /* affichage des nouvelles images à l'écran */
  $("#img_earth").attr("src", "img/xplanet_earth.png?"+now);

  xplanet_timeout = setTimeout("xplanet()", 100000);
}

/* RSS */

var rss_timeout;

function rss ()
{
  $.ajax({
    async : false,
    type: "GET",
    url: "./ajax.php",
    data: "block=rss",
    success: function(html){
      $("#rss").html(html);
    }
  });

  rss_timeout = setTimeout("rss()", 60000);
}

var horloge_timeout;
Date.prototype.addHours = function(h) {
   this.setTime(this.getTime() + (h*60*60*1000));
   return this;
}
function horloge()
{
  dows  = ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"];
  mois  = ["janv", "f&eacute;v", "mars", "avril", "mai", "juin", "juillet", "ao&ucirc;t", "sept", "oct", "nov", "d&eacute;c"];

  now          = new Date;
  heure        = now.addHours(1).getHours();
  min          = now.getMinutes();
  sec          = now.getSeconds();
  jour_semaine = dows[now.getDay()];
  jour         = now.getDate();
  mois         = mois[now.getMonth()];
  annee        = now.getFullYear();

  if (sec < 10){sec0 = "0";}else{sec0 = "";}
  if (min < 10){min0 = "0";}else{min0 = "";}
  if (heure < 10){heure0 = "0";}else{heure0 = "";}

  horloge_heure   = heure0 + heure + ":" + min0 + min;
  horloge_date    = "<span class='horloge_grey'>" + jour_semaine + "</span> " + jour + " " + mois + " <span class='horloge_grey'>" + annee + "</span>";
  horloge_content = "<div class='horloge_heure'>" + horloge_heure + "</div><div class='horloge_date'>" + horloge_date + "</div>";

  $("#horloge").html(horloge_content);

  horloge_timeout = setTimeout("horloge()", 1000);
}



/* meteo */

var meteo_timeout;

function meteo ()
{
  $.ajax({
    async : false,
    type: "GET",
    url: "./ajax.php",
    data: "block=meteo",
    success: function(html){
      $("#meteo").html(html);
    }
  });

  meteo_timeout = setTimeout("meteo()", 3600000);
}

/* checkHue */
var hue_timeout;
function checkhue()
{
  $.ajax({
    async : false,
    type: "GET",
    url: "./ajax.php",
    data: "block=checkhue",
    success: function(html){
      $("#checkhue").html(html);
    }
  });

  hue_timeout = setTimeout("checkhue()", 60000);
}

conte = document.getElementById('cont_NTkzNTB8NHwzfDF8M3xGOEEwQzJ8MnxGRkZGRkZ8Y3wx');enlace = document.getElementById('spa_NTkzNTB8NHwzfDF8M3xGOEEwQzJ8MnxGRkZGRkZ8Y3wx');anchor = document.getElementById('a_NTkzNTB8NHwzfDF8M3xGOEEwQzJ8MnxGRkZGRkZ8Y3wx');var url = anchor.href;var ua = navigator.userAgent.toLowerCase();check = function(r){return r.test(ua);};isWebKit = check(/webkit/);isGecko = !isWebKit && check(/gecko/);var text = '';if(isGecko){text = encodeURI(anchor.text);} else { text = encodeURI(anchor.innerText); } var exp1 = new RegExp('http://www.meteocity.com'); var exp2 = new RegExp('M%C3%A9t%C3%A9o'); var exp3 = new RegExp('o'); if (conte && enlace && anchor && exp1.test(url) && (exp2.test(text) || exp3.test(text)) ){ enlace.style.cssText = 'font:normal 10px/12px Tahoma, Arial, Helvetica, serif; color:#333; padding:0 0 3px; text-decoration: none;'; conte.style.cssText = 'width:480px;'; elem = document.createElement('iframe'); elem.id = 'NTkzNTB8NHwzfDF8M3xGOEEwQzJ8MnxGRkZGRkZ8Y3wx'; elem.src = 'http://widget.meteocity.com/NTkzNTB8NHwzfDF8M3xGOEEwQzJ8MnxGRkZGRkZ8Y3wx/'; elem.frameBorder = 0; elem.allowTransparency = true; elem.scrolling = 'no'; elem.name = 'frame'; elem.height = '200'; elem.width = '480'; conte.insertBefore(elem,enlace); }


