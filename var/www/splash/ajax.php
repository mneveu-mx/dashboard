<?php

  header('Content-type: text/html; charset=utf-8');
  require_once('inc.php');
  require_once('./rss/rss_php.php');  

  if(isset($_REQUEST['block'])){$block = $_REQUEST['block'];}else{$block = 'none';}
  /////////////////////////////////////////////////
  //  LATENCY
  /////////////////////////////////////////////////

  if($block == 'latency'){
    echo latency();
  }

  /////////////////////////////////////////////////
  //  TS3
  /////////////////////////////////////////////////

  else if($block == 'ts3'){
    echo ts3();
  }

  /////////////////////////////////////////////////
  //  METEO
  /////////////////////////////////////////////////

  else if($block == 'meteo'){
    echo meteo();
  }
  else if($block == 'checkhue'){
    echo checkhue();
  }
  else if($block == 'checkchromecast'){
    echo checkchromecast();
  }
  else if($block == 'gethuesalon'){
    echo gethuesalon();
  }
  else if($block == 'gethuesdb'){
    echo gethuesdb();
  }

  /////////////////////////////////////////////////
  //  PING
  /////////////////////////////////////////////////

  else if($block == 'ping'){
    echo ping();
  }

  /////////////////////////////////////////////////
  //  VPN PPTPD
  /////////////////////////////////////////////////

  else if($block == 'vpn'){
    echo checkhue();
  }else if($block == 'rss'){
    echo rss();
  }

  /////////////////////////////////////////////////
  //  IFSTAT
  /////////////////////////////////////////////////
  else if($block == 'ifstat'){
    imagickHisto ($_REQUEST['max'], $_REQUEST['eth'], $_REQUEST['up_down']);
  }

?>
