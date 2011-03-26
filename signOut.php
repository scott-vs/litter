<?php
/*
 * signOut.php
 * 
 * Logs user off of Litter and deletes demo environment.
 *  
 * Litter is coded by Scott VonSchilling. He needs a job. If you like
 * what you see, please email scottvonschilling [at] gmail [dot] com.
 * 
 */

require_once('utils.php');

$sql = openSQL();
mysql_select_db("litter", $sql);

// expire cookie
setcookie('litterID','',1);

// delete from DB
mysql_query("DELETE FROM tolkens WHERE litter_id = ".$_COOKIE['litterID'],$sql);	
mysql_query("DROP TABLE litt_".$_COOKIE['litterID'],$sql);
mysql_query("DROP TABLE users_".$_COOKIE['litterID'],$sql);	


?>
<script type="text/javascript">
	location.href = "./";
</script>