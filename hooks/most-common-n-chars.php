<?php
	/* Assuming this custom file is placed inside 'hooks' */
	define('PREPEND_PATH', '../');
	$hooks_dir = dirname(__FILE__);
	include("{$hooks_dir}/../defaultLang.php");
	include("{$hooks_dir}/../language.php");
	include("{$hooks_dir}/../lib.php");
	
	include_once("{$hooks_dir}/../header.php");
	
	$customers = [];
	$res = sql("SELECT `CompanyName` FROM `customers`", $eo);
	while($row = db_fetch_assoc($res)){
		$customers[] = preg_replace('/[\W\d_]/', '', $row['CompanyName']);
	}

	$chunks = [];
	foreach($customers as $c) {
		$chunks = array_merge($chunks, mostCommonNChars($c, 2));
	}

	$freq = [];
	foreach ($chunks as $ch) $freq[$ch]++;
	$freq = array_filter($freq, function($e) { return $e > 5; });
	arsort($freq);

	var_dump($freq);

	function mostCommonNChars($str, $n) {
		$chunks = [];
		for($i = 0; $i < $n; $i++) {
			$chunks = array_merge($chunks, str_split(strtolower(substr($str, $i)), $n));
		}
		return array_filter($chunks, function($e) use($n) { return strlen($e) == $n; });
	}

	include_once("{$hooks_dir}/../footer.php");