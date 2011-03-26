<?php
/*
 * newLitt.php
 * 
 * AJAX call to add a new litt from logged in user.
 *  
 * Litter is coded by Scott VonSchilling. He needs a job. If you like
 * what you see, please email scottvonschilling [at] gmail [dot] com.
 * 
 */

	require_once 'utils.php';
	require_once 'classes/Litt.php';
	
	$return;
	$return->status = "ok";
	
	($_POST["reply"]) ? $replyTo = new BigInt(substr($_POST["reply"], 1)) : $replyTo = 0;
	
	$me = new User($_COOKIE['litterID']);
	$me->loadInfoFromDB();
	
	$newlitt = Litt::createNewLitt($me, $_POST["text"], $replyTo);
	$newlitt->saveToMasterDB($sql,$_COOKIE['litterID']);
	$return->id = "l".$newlitt->getID();
	$return->text = $newlitt->printLitt();
	
	echo(json_encode($return));

?>