<?php
/*
 * cleanup.php
 * 
 * This cron job looks at the Litter tolkens table and cleans out
 * all expired Litter IDs. Also pulls Twitter for new sample data.
 * 
 * Litter is coded by Scott VonSchilling. He needs a job. If you like
 * what you see, please email scottvonschilling [at] gmail [dot] com.
 * 
 */

require_once 'utils.php';

$sql = openSQL();
mysql_select_db("litter", $sql);

require "twitterPull.php";

$select = mysql_query("SELECT * FROM tolkens WHERE expires < ".time(),$sql);
	
while ($row = mysql_fetch_array($select)){
	$myID = $row["litter_id"];
	
	mysql_query("DELETE FROM tolkens WHERE litter_id = ".$myID,$sql);	
	mysql_query("DROP TABLE litt_".$myID,$sql);
	mysql_query("DROP TABLE users_".$myID,$sql);	
}

mysql_close($sql);

?>