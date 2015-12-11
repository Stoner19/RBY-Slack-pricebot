<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("functions.php");
require("exchange.php");
	$excDataArray = ""; // contains high, low, bid, ask, volume, currency, exchange (no last price - thanks Bitstamp!
	setSlackToken("put your slack token here");
	setSlackMessage($_POST);
	setExchangeConfig();
	
// commands: troll, price
// parameters: btx, polo

	$findspace   = ' ';
	$cmd = substr(getSlackMessageText(), 0, 5); // the command is 5 characters long and always at the beginning of the line.
	$cmd = strtolower($cmd);
if($cmd == 'price'){
	$botname = "RBY Price Bot";
	$pos = strpos(getSlackMessageText(), $findspace);
	$exc = trim(substr(getSlackMessageText(), $pos));
	$exc = strtolower($exc);
	if($exc == 'gox' || $exc == 'mtgox'){
		$botname = "MtGox Price Bot";
		$text = "Seriously? You're seriously going to try and check the price on an exchange that went bankrupt? How do I even quote that... ? Try 'troll' instead";
		// technically, last price at gox was 135.69
		$arr = array ('text'=>$text,'username' => $botname);
		// clearly the user is trolling us, so we end the script
    	echo json_encode($arr);
		exit(0);
	} else if($exc == '!'){
		$text= "Commands are case sensitive. Use 'price <exchange>' format where <exchange> is bfx, bs, RBYe, okc. Use 'all' to get data from all exchanges in one response";
	} else if($exc == 'all'){
		$excDataArray = getLatest('btx');
		$text = formatOutput($excDataArray, "");
		
		$excDataArray = getLatest('polo');
		$text .= formatOutput($excDataArray, "");
		
	} else {
		$excDataArray = getLatest('btx');
		$text = formatOutput($excDataArray, "");
		
		$excDataArray = getLatest('polo');
		$text .= formatOutput($excDataArray, "");
	}
	
} 
// else if($cmd == 'troll'){
// 	$botname = "Mark K. - Professional Goxxer";
// 	$randValue = mt_rand(0, 23);
// 	$text = "";
// $username = getSlackMessageUserName();
// 	switch($randValue){
// 		case 1: $text = "Hey ".$username."... prepare yourself for a proper GOXXING! Frappuccinos for me!"; break;
// 		case 2: $text = "Dude, where's my keys ".$username."?"; break;
// 		case 2: $text = "Fuck off! I'm tryna get some slackbot ass ".$username."!"; break;
// 		case 4: $text = $username."... how's that MOON treating ya?"; break;
// 		case 5: $text = "I don't like that ".$username." guy... he's always trolling when he speaks"; break;
// 		case 6: $text = "Oh, what's this? 200K RBY in my sock?!"; break;
// 		case 7: $text = "Rubbing that salt in your wound since I could drink frappuccinos."; break;
// 		case 8: $text = "RBY landed on the moon and my fatass somehow shat out 200K RBY."; break;
// 		case 9: $text = "Y U NO drink Frappuccino, ".$username."?"; break;
// 		case 10: $text = "I was goxxed, ".$username.". I now drink frappuccinos out of dirty assholes. I call them assuccinos."; break;
// 		case 11: $text = "My exchange tanks while shady ass RBY-e still thrives, and they're the criminals?"; break;
// 		case 12: $text = $username." put your only copy of private keys on a USB and format it bro"; break;
// 		case 13: $text = $username." one does not simply avoid frappuccino before coding session"; break;
// 		case 14: $text = $username.", you should check to see if your cold storage is leaking."; break;
// 		case 15: $text = "Waves moon at ".$username." Never gonna get this!"; break;
// 		case 16: $text = $username.", are you on windows? Try ALT+F4 to collect your lost coins!"; break;
// 		case 17: $text = $username.", buy high; sell low. Strategy so bulletproof it can't be goxxed."; break;
// 		case 18: $text = ":fu:".$username.", :fu: hard."; break;
// 		case 19: $text = $username." make it digitally hail on those analog hoes."; break;
// 		case 20: $text = "Hey ".$username.", ever been goxxed?"; break;
// 		case 21: $text = "Choo choo, ".$username.". Can't go to the moon without my willy"; break;
// 		case 22: $text = "_Sets root password to 'mtgox'_ ".$username.", my exchange cannot be haxxed now"; break;
// 		case 23: $text = "Frappuccino (noun): A drink known by the world for being consumed by the system administrator of the largest bitcoin exchange on the planet (that's me, bitches). I spilled it on the keyboard of the laptop containing private keys, effectively reducing the bitcoin supply by 7%. This drink may be the sole reason people were goxxed."; break;
// 		default: $text = "_Nelson_ ha ha (points at ".$username.")"; break;
// 	}
// } 
else {
	$text = "Some type of error occured. DEBUG: ".$cmd." length cmd = ".strlen($cmd);
	$botname = "ERROR";
}
    // and now we package the return response we've selected above in the slack API format and encode it to json.
    $arr = array ('text'=>$text,'username' => $botname);

    echo json_encode($arr);
	exit(0)
?>
