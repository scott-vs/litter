<?php 
/*
 * id_tag.php
 * 
 * Part of the main / settings page that identifies the logged in user.
 * 
 * Litter is coded by Scott VonSchilling. He needs a job. If you like
 * what you see, please email scottvonschilling [at] gmail [dot] com.
 * 
 */
	require_once 'utils.php';
   	require_once 'classes/Litt.php';
   	
   	if (!$sql){
   		$sql = openSQL();
   		mysql_select_db("litter", $sql);
   	}
   	
   	$me = new User($_COOKIE["litterID"]);
    $me->loadInfoFromDB();
    
    $myName = $me->getRealName();
	$myImage = $me->getLargeImageUrl();
?>

<div id="id_tag">
	<img src="<?php echo($myImage);?>" alt="Your user picture." /> 
	<p>Welcome back to Litter, <?php echo($myName);?>!<br />
	<a href="./settings.php">Settings</a> | <a href="./signOut.php" onclick="return confirm('Are you sure you want to sign out? If you sign out, your demo will over, your data will be deleted, and you will return to the welcome page.');">Sign Out</a></p>
</div>