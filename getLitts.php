<?php
/*
 * getLitts.php
 * 
 * AJAX call to get older or newer litts than what is currently 
 * displayed on the home page. 
 *  
 * Litter is coded by Scott VonSchilling. He needs a job. If you like
 * what you see, please email scottvonschilling [at] gmail [dot] com.
 *  
 */
	require 'demoScript.php';
	require_once 'utils.php';
	require_once 'variables.php';
	require_once 'classes/Litt.php';
	
	$sql = openSQL();
	mysql_select_db("litter", $sql);
	
	$return;
	if ($_GET['before']){
		// Get newer Litts to be added at the top of the home screen.
		$return->status="ok";
		$top = $_GET['before'];
		
		$littTbl = "litt_".$_COOKIE['litterID'];
	   	$userTbl = "users_".$_COOKIE['litterID'];
	   	$q = "SELECT * FROM $littTbl, $userTbl WHERE $littTbl.user_id = $userTbl.user_id AND $littTbl.litt_id > $top ORDER BY litt_id DESC";
	   	$result = mysql_query($q,$sql);
		$isFirst = TRUE;
		$s = "";
	   	while ($row = mysql_fetch_array($result)) {
	   		$l = Litt::importFromDB($row);
	   		if ($isFirst){
	   			$top = $l->getID();
	   			$isFirst = FALSE;
	   		}
				$s .= $l->printLitt();
		}
		$return->top = $top;
		$return->text = $s;
		
	} else if ($_GET['after']){
		// Get older litts to be added at the bottom of the home screen.
		$return->status="ok";
		$bottom = $_GET['after'];
		
		$litt = "litt_".$_COOKIE['litterID'];
	   	$user = "users_".$_COOKIE['litterID'];
	   	$result = mysql_query("SELECT * FROM $litt, $user WHERE $litt.user_id = $user.user_id AND $litt.litt_id < $bottom ORDER BY litt_id DESC LIMIT 0, 10", $sql);

		$s = "";
	   	while ($row = mysql_fetch_array($result)) {
	   		$l = Litt::importFromDB($row);
	   		$s .= $l->printLitt();
		}
		$return->bottom = $l->getID();
		$return->text = $s;
		
	} else {
		$return->status="error";
	}
	
	echo(json_encode($return));

	mysql_close($sql);
?>