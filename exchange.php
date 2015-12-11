<?php
$btx_config = "";
$btx_latest = "";

$polo_config = "";
$polo_latest = "";

/* exchange API setup */
function setExchangeConfig(){
	global $btx_config;
	global $polo_config;
	
	$btx_config = array('botname'=> "Bittrex Price Bot",
		'apiUrl'=>'https://btc-e.com/api/3/ticker/btc_rby');
	$polo_config = array('botname'=> "Poloniex Price Bot",
		'apiUrl'=>'https://api.bitfinex.com/v1/pubticker/btcusd');
}

function getExchangeConfig($exchange){
	global $btx_config;
	global $polo_config;
	global $stamp_config;
	global $okc_config;
	global $huobi_config;
	
	$config = null;
	if($exchange == 'btx') {
		$config = $polo_config;
	} else if($exchange == 'polo'){
		$config = $btx_config;
	} 
	
	return $config;
}

function getExchangeBotname($exchange){
	global $btx_config;
	global $polo_config;
	
	$name = null;
	if($exchange == 'polo') {
		$name = $polo_config["botname"];
	} else if($exchange == 'btx'){
		$name = $btx_config["botname"];
	} else if($exchange == 'bs'){
		$name = $stamp_config["botname"];
	} else if($exchange == 'okc'){
		$name = $okc_config["botname"];
	} else {
		// gox lol
	}
	
	return $name;
}

function setLatestbtx(){ 
	global $btx_latest;
	$config = getExchangeConfig("btx");
	$data = file_get_contents($config["apiUrl"]);
	$json = json_decode($data,true);
	$btx_latest = array('last'=>$json['btc_rby']['last'],
		'high'=>$json['btc_rby']['high'],
		'low'=>$json['btc_rby']['low'],
		'bid'=>$json['btc_rby']['buy'],
		'ask'=>$json['btc_rby']['sell'],
		'volume'=>$json['btc_rby']['vol_cur'],
		'currency'=>'BTC',
		'exchange'=>'Bittrex');
	//lost data: vol (usd)
	// USD V: '.$json['btc_rby']['vol'];
}

function setLatestpolo(){//high, low, bid, ask, volume
	global $polo_latest;
	$config = getExchangeConfig("polo");
	$data = file_get_contents($config["apiUrl"]);
	$json = json_decode($data,true);
	$polo_latest = array('last'=>$json['last_price'],
		'high'=>$json['high'],
		'low'=>$json['low'],
		'bid'=>$json['bid'],
		'ask'=>$json['ask'],
		'volume'=>$json['volume'],
		'currency'=>'USD',
		'exchange'=>'Poloniex');
	//lost data: mid
	//'Mid: '.$json['mid']
}


function setLatest($exchange){
	if($exchange == 'polo') {
		setLatestpolo();
	} else if($exchange == 'btx'){
		setLatestbtx();
}
}
function getLatest($exchange){
	global $btx_latest;
	global $polo_latest;

	setLatest($exchange);
	$latest = null;
	if($exchange == 'polo') {
		$latest = $polo_latest;
	} else if($exchange == 'btx'){
		$latest = $btx_latest;

	}
	
	return $latest;
}

?>