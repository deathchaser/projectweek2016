<?php

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

/*echo $twitterContent;*/

$twitterContentWords = array_count_values(str_word_count($twitterContent, 1));
/*echo "BLu";
print_r($twitterContentWords);
echo "Blu";*/

$defaultLines = array();
$filter = array("http", "whores", "followers", "pussy", "retweet", "t", "did", "am", "already", "i", "you", "he", "we", "them", "the","of","to","and","a","in","for","is","The","that","on","said","with","be","was","by","as","are","at","from","it","has","an","have","will","or","its","he","not","were","which","this","but","can","more","his","been","would","about","their","also","they","had","than","up","who","In","one","you","new","A","I","other","all","two","S","But","It","into","U","Mr.", "please","some","when","out","last","only","after","first","time","says","He", "no","over","we","could","if","such","This","most","use","because","any","there","them","may","so","New","now","many", "good","new", "long","great","little","own","other","old","right","big","high","different","small","large","next","early","few","same","be","have","do", "so", "how","then","here","well","only","very","even","back","there","down","still","in","as","too","when","never","really","most","of","in","to","for","with","on","at","from","by","about","as","into","like","through","after","over","out","one","her","us","something","nothing","anything","himself","everything","someone","themselves","everyone","itself","anyone","myself","and","that","but","or","as","if","when","than","because","while","where","after","so","though","since","until","whether","before","although","nor","like","once","unless","now","except","one","two","first","last","three","next","million","four","five","second","six","third","billion","hundred","thousand","seven","eight","ten","nine","dozen","fourth","twenty","fifth","thirty","yes","oh","yeah","no","hey","hi","hello","hmm","ah","wow","the","be","to","of","and","a","in","that","have","I","it","for","not","on","with","he","as","you","do","at","this","but","his","by","from","they","we","say","her","she","or","an","will","my","one","all","would","there","their","what","so","up","out","if","about","who","get","which","go","me","when","make","can","like","time","no","just","him","know","take","person","into","year","your","good","some","could","them","see","other","than","then","now","look","only","come","its","over","think","also","back","after","use","two","how","our","work","first","well","way","even","new","want","because","any","these","give","day","most","us");

function countStr($str) {
	$words = array_count_values(str_word_count($str, 1));
	return $words;
}

function isInFilter($word, $filter) {
	return in_array($word, $filter);
}

function countUses($word, $defaultLines) {
	$count = 1;
	foreach($defaultLines as $key => $defaultLine) {
		if(isset($defaultLine[$word])) {
			$count ++;
		}
	}
	return $count;
}

/**
 * uses watson emotion analyzer
 * @param  string $text text to send to watson
 * @return json as string       watson result
 */
function useWatson($text) {
	/*rtrim($text, '&');
	$host = "https://c05b24fd-cb71-48ea-bfeb-b81ff5bffcff:pHZwHBOSBOIR@gateway.watsonplatform.net/tone-analyzer-beta/api/v3/tone?version=2016-02-11&text=" . urlencode($text);
	//$host = "https://c05b24fd-cb71-48ea-bfeb-b81ff5bffcff:pHZwHBOSBOIR@gateway.watsonplatform.net/tone-analyzer-beta/api/v3/tone?version=2016-02-11&text=ditistext";
	
	$contentJson = file_get_contents($host);
	return $contentJson;*/
	rtrim($text, '&');
	$url = 'https://c05b24fd-cb71-48ea-bfeb-b81ff5bffcff:pHZwHBOSBOIR@gateway.watsonplatform.net/tone-analyzer-beta/api/v3/tone?version=2016-02-11';
	$data = array('text' => urlencode($text));

	// use key 'http' even if you send the request to https://...
	$options = array(
	    'http' => array(
	        'header'  => "Content-type: text/plain",
	        'method'  => 'POST',
	        'content' => http_build_query($data),
	    ),
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if ($result === FALSE) { echo "watson doe moeilijk"; return false;}

	return $result;
}



$lines = file('lines.txt');
for($i = 0;$i < count($lines); $i++) {
	$defaultLines[$i] = countStr(strtolower($lines[$i]));
}

/*print_r($defaultLines);*/

/*echo countUses("age", $defaultLines);*/

foreach($twitterContentWords as $w => $a) {
	$usedCount = countUses($w, $defaultLines);
	if(isInFilter($w, $filter) || strlen($w) <= 3 || $w == $search) {
		$twitterContentWords[$w] = 0;
	}else{
		$twitterContentWords[$w] = $a / countUses($w, $defaultLines);
	}
}

arsort($twitterContentWords);
/*print_r($twitterContentWords);*/

$twitterContentWords = array_slice($twitterContentWords, 0, 10);


$resultJson = '{"words":[';

foreach($twitterContentWords as $w => $a) {
	$resultJson .= '{"word":"' . $w . '", "score":"' . $a . '"},';
}

$resultJson = substr($resultJson, 0, -1);

$resultJson .= ']';

/*echo $resultJson;
*/

$watsonDataString = useWatson($twitterContent);
if($watsonDataString == false) {

}else{
	$watsonData = json_decode($watsonDataString);

	$tones = $watsonData->document_tone->tone_categories[0]->tones;

	$resultJson .= ',"tones":[';

	foreach($tones as $tone) {
		$resultJson .= '{"tone":"' . $tone->tone_id . '", "score":"' . $tone->score . '"},'; 
	}
	$resultJson = substr($resultJson, 0, -1);

	$resultJson .= ']';
}

$resultJson .= '}';


echo $resultJson;

?>