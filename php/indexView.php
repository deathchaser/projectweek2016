<?php

require_once("tfidf.php");

$requestUrl = 'https://ba4f9524-07de-4ac5-82e3-58af008724e2:0gssliiq3f@cdeservice.mybluemix.net/api/v1/messages/search';
$search = "ucll";
$size = 20;
$lang = "en";
$from = 0;


if(isset($_GET['search']))
	$search = $_GET['search'];

if(isset($_GET['size']))
	$size = $_GET['size'];

if(isset($_GET['lang']))
	$lang = $_GET['lang'];

if(isset($_GET['from']))
	$from = $_GET['from'];

$content_json = file_get_contents($requestUrl . '?q=%23' . $search . '&size=' . $size . '&from=' . $from);
$content = json_decode($content_json);

/*echo $content_json;*/

$twitterContent = "";

foreach($content->tweets as $tweet) {
	if($lang == "none" || $tweet->message->twitter_lang == $lang)
		$twitterContent .= " " . strtolower($tweet->message->body);
}

echo $twitterContent;

$twitterContentWords = array_count_values(str_word_count($twitterContent, 1));
echo "BLu";
print_r($twitterContentWords);
echo "Blu";



$lines = "";



$tfidf = new Tfidf();

print_r($tfidf->getDefaults());




?>