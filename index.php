<?php
/*
 * index.php
 * 
 * The launching pad of the Litter front end.
 *  
 * Litter is coded by Scott VonSchilling. He needs a job. If you like
 * what you see, please email scottvonschilling [at] gmail [dot] com.
 * 
 */

// Cookie check after setup.
if (isset($_GET["cookie"])){
	if(isset($_COOKIE['litterID']))
		exit('<script type="text/javascript"> location.href = "./"; </script>');
	else 	
		exit('Sorry, this demo requires that you have cookies enabled. <a href="./">Go home.</a>');
} 


if(isset($_COOKIE['litterID']))
	require_once 'pages/home.php';
else 
	require_once 'pages/welcome.php';
	
?>