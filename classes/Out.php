<?php

class Out {

	protected static $buffer = '';

	public static function add($content) {
		self::$buffer .= $content;
	}

	public static function printf() {
		self::$buffer .= call_user_func_array('sprintf', func_get_args());
	}

	public static function renderPage() {
		$content = self::$buffer . "\n";
		require 'templates/default.php';
	}

}
