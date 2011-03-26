<?php
/* 
 * twitterPull.php
 * 
 * Pull latest feeds from Twitter API
 *  
 * Litter is coded by Scott VonSchilling. He needs a job. If you like
 * what you see, please email scottvonschilling [at] gmail [dot] com.
 * 
 */
require_once 'variables.php';
require_once 'utils.php';
require_once 'classes/Litt.php';
require_once 'classes/User.php';

if (!function_exists(json_decode)) exit("PHP 5.2 required for JSON.");

if (!$sql){
	$sql = openSQL();
	mysql_select_db("litter", $sql);
}

foreach ($USER_LIST as $u){
	$feedUrl=("http://api.twitter.com/1/statuses/user_timeline.json?screen_name=".$u);
	$ch = curl_init();	
	
	curl_setopt($ch, CURLOPT_URL, $feedUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 8);
			
	$feed_content = curl_exec($ch);
	
	$tweets = json_decode($feed_content, TRUE);
	
	if ($tweets["error"] == "Rate limit exceeded. Clients may not make more than 150 requests per hour.") // Rate limit has been reached, cancel import
		return;
	else if ($tweets["error"])
		continue;
	
	
	$userInfo = $tweets[0]["user"];
	$user = User::importFromTwitter($userInfo);
	$user->saveToDB($sql);
	
	foreach ($tweets as $tweet){
		if ($tweet["in_reply_to_user_id"]) continue;  // ignore any @reply tweets
		$litt = Litt::importFromTwitter($tweet);
		$litt->setUser($user);
		$litt->saveToMasterDB($sql);
	}

}


?>