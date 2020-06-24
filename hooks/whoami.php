<?php
	$hooks_dir = dirname(__FILE__);
	include("{$hooks_dir}/../defaultLang.php");
	include("{$hooks_dir}/../language.php");
	include("{$hooks_dir}/../lib.php");
	
	print_r(getMemberInfo());
	print_r($_SERVER);
	print_r(apache_request_headers());