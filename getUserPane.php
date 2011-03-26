<?php
/*
 * getUserPane.php
 * 
 * AJAX call to get user info and display is on the home page.
 *  
 * Litter is coded by Scott VonSchilling. He needs a job. If you like
 * what you see, please email scottvonschilling [at] gmail [dot] com.
 * 
 */
	require_once 'utils.php';
	require_once 'classes/User.php';
	
	$return;
	if ($_GET["id"]){
		$myID = $_GET["id"];
		
		$sql = openSQL();
   		mysql_select_db("litter", $sql);	
   		
   		$me = new User($myID);
  		$me->loadInfoFromDB();
		
  		$return->status = "ok";
  		$return->text = $me->printUserPane();
	} else 
		$return->status = "Error: no user id defined.";
		
	echo (json_encode($return));
	
	
?>