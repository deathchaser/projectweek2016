<?php

require_once("line.php");

class Tfidf {

	private $defaultLines;
	private $filter;

	public function __constructor() {
		getDefaults();
		$filter = array("the", "an", "");
	}

	public function countUses($word) {
		$count = 1;
		if(isInFilter($word)) {
			return $count;
		}
		foreach($defaultLines as $defaultLine) {
			if(isset($defaultLine[$word])) {
				$count ++;
			}
		}
		return $count;
	}

	private function getDefaults() {
		$lines = file('');
		foreach($lines as $line_num => $line) {
			$defaultLines = countStr(strtolower($line));
		}
	}

	private function countStr($str) {
		$words = array_count_values(str_word_count($str, 1));
		return $words;
	}

	private function isInFilter($word) {
		return in_array($word, $filter);
	}



}


?>