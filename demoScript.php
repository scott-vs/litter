<?php
/*
 * demoScript.php
 * 
 * Simulates other users on Litter updating in real-time.
 * 
 * Litter is coded by Scott VonSchilling. He needs a job. If you like
 * what you see, please email scottvonschilling [at] gmail [dot] com.
 *
 */
require_once 'utils.php';
require_once 'variables.php';
require_once 'classes/Litt.php';
require_once 'classes/User.php';

if (!$sql){
	$sql = openSQL();
	mysql_select_db("litter", $sql);
}

$littTbl = "litt_".$_COOKIE['litterID'];
$userTbl = "users_".$_COOKIE['litterID'];
	
// Import new litts from Twitter

$ex = mysql_fetch_array(mysql_query("SELECT expires FROM tolkens WHERE litter_id = ".$_COOKIE['litterID'],$sql));

$ex2 = $ex[0];
$phase = $_COOKIE['litterPhase'];
$minutes = 120 - ($ex2 - time()) / 60;

function copyTweet($n){
	global $littTbl, $sql;
	
	$n = 25 - $n;
	$q = "INSERT INTO $littTbl SELECT * FROM litt_master ORDER BY litt_id DESC LIMIT $n , 1";
	mysql_query($q,$sql);
}

switch ($phase){
	case 0:
		if ($minutes > 1){
			copyTweet(1);
			$phase = 1;
		}
	case 1:
		if ($minutes > 2){
			copyTweet(2);
			copyTweet(3);
			$phase = 2;
		}
	case 2:
		if ($minutes > 3){
			copyTweet(4);
			$phase = 3;
		}
	case 3:
		if ($minutes > 4){
			copyTweet(5);
			copyTweet(6);
			$phase = 4;
		}
	case 4:
		if ($minutes > 5.5){
			copyTweet(7);
			copyTweet(8);
			copyTweet(9);
			$phase = 5;
		}
	case 5:
		if ($minutes > 7){
			copyTweet(10);
			$phase = 6;
		}
	case 6:
		if ($minutes > 8){
			copyTweet(11);
			copyTweet(12);
			$phase = 7;
		}
	case 7:
		if ($minutes > 9){
			copyTweet(13);
			$phase = 8;
		}
	case 8:
		if ($minutes > 10){
			copyTweet(14);
			copyTweet(15);
			copyTweet(16);
			$phase = 9;
		}
	case 9:
		if ($minutes > 11){
			copyTweet(17);
			$phase = 10;
		}
	case 10:
		if ($minutes > 12){
			copyTweet(18);
			copyTweet(19);
			$phase = 11;
		}
	case 11:
		if ($minutes > 13){
			copyTweet(20);
			$phase = 12;
		}
	case 12:
		if ($minutes > 14){
			copyTweet(21);
			$phase = 13;
		}
	case 13:
		if ($minutes > 20){
			copyTweet(22);
			$phase = 14;
		}
	case 14:
		if ($minutes > 27){
			copyTweet(23);
			$phase = 15;
		}
	case 15:
		if ($minutes > 39){
			copyTweet(24);
			$phase = 16;
		}
	case 16:
		if ($minutes > 52){
			copyTweet(25);
			$phase = 17;
		}
	default: break;
}

setcookie("litterPhase", $phase, $ex2);

// Mimi's Tweets

$result = mysql_query("SELECT * FROM $littTbl, $userTbl WHERE $littTbl.user_id = $userTbl.user_id AND user_name = 'Wild_Tiger'");
$tigerNum = mysql_num_rows($result);
$result = mysql_query("SELECT * FROM $littTbl, $userTbl WHERE $littTbl.user_id = $userTbl.user_id AND user_name = 'Classy_Mimi'");
$mimiNum = mysql_num_rows($result);


// Random, spur-of-the-moment Mimi thought
if (!rand(0,50))
	newMimiLitt(FALSE);	

	
// Respond to Tiger
if ($tigerNum > $mimiNum){
	if (!rand(0,10)){
		newMimiLitt(rand(0,1));	
	}
}

mysql_close($sql);
	
function newMimiLitt($reply){
	global $sql, $littTbl, $userTbl, $MIMI_THOUGHTS, $MIMI_REPLIES;
	$mimi = new User(1234567);
	$mimi->loadInfoFromDB();
	
	if ($reply){
		$q ="SELECT litt_id FROM $littTbl, $userTbl WHERE $littTbl.user_id = $userTbl.user_id AND user_name = 'Wild_Tiger' ORDER BY litt_id DESC LIMIT 0,1";
		$result = mysql_query($q);
		$myID = mysql_fetch_row($result);
		$rID = new BigInt($myID[0]);
		$text = "@Wild_Tiger ". $MIMI_REPLIES[rand(0, count($MIMI_REPLIES)-1)];
		
	} else {
		$rID = 0;
		$text = $MIMI_THOUGHTS[rand(0, count($MIMI_THOUGHTS)-1)];
		
	}
	$mimiLitt = Litt::createNewLitt($mimi, $text, $rID);
	$mimiLitt->saveToMasterDB($sql, $_COOKIE["litterID"]);
	
}

?>