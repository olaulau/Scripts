<?php
/**
 * some PHP 8.x only functions written for PHP7.x support (needed for debian 11 until 2026-06)
 */

// https://www.php.net/manual/fr/function.str-contains.php#125977
if (!function_exists('str_contains')) {
	function str_contains($haystack, $needle)
	{
		return $needle !== '' && mb_strpos($haystack, $needle) !== false;
	}
}

if (!function_exists('str_starts_with')) {
	function str_starts_with($haystack, $needle)
	{
		return $needle !== '' && mb_strpos($haystack, $needle) === 0;
	}
}
