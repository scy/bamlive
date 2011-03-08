<?php

class Util {

	protected static $splitPath = null;

	public static function autoload($class) {
		$class = preg_replace('#[^A-Za-z0-9_]#', '', $class);
		@include_once "classes/$class.php";
	}

	public static function callHandler() {
		$handler = self::getHandler();
		if (method_exists($handler[0], $handler[1])) {
			return call_user_func($handler);
		} else {
			ErrorHandler::handleGet404();
		}
	}

	/**
	 * Retrieve the base URL path of the installation.
	 *
	 * E.g. '/~scy/bamlive'.
	 */
	public static function getBasepath() {
		return dirname($_SERVER['PHP_SELF']);
	}

	public static function getCleanPath() {
		// TODO: Clean the path.
		return self::getPath();
	}

	public static function getHandler() {
		$path = explode('/', substr(self::getCleanPath(), 1), 3);
		if (!is_array($path) || $path[0] === '') {
			$path = array('home');
		}
		if (count($path) < 2) {
			$path[1] = 'index';
		}
		self::$splitPath = $path;
		$class = ucfirst(strtolower($path[0])) . 'Handler';
		$func = 'handle'
		      . ucfirst(strtolower($_SERVER['REQUEST_METHOD']))
		      . ucfirst(strtolower($path[1]));
		return array($class, $func);
	}

	/**
	 * Retrieve the path of the requested document, relative to the base path.
	 *
	 * E.g. '/bleh/blah'.
	 */
	public static function getPath() {
		$doesmatch = preg_match(
			'#^' . self::getBasepath() . '(|/.*)$#',
			$_SERVER['REQUEST_URI'],
			$matches
		);
		if ($doesmatch) {
			return $matches[1];
		}
		self::redirect('/');
	}

	/**
	 * Redirect to an absolute path within the application.
	 */
	public static function redirect($to) {
		$s = substr($_SERVER['SERVER_PROTOCOL'], 0, 5) == 'HTTPS' ? 's' : '';
		$port = (int)$_SERVER['SERVER_PORT'];
		$url = "http$s://" . $_SERVER['SERVER_NAME'] .
		       ((($s == '' && $port == 80) || ($s == 's' && $port == 443)) ? '' : ":$port") .
		       self::getBasepath() . $to;
		header("Location: $url");
		exit();
	}

}

function __autoload($class) {
	return Util::autoload($class);
}

Util::callHandler();
Out::renderPage();
