<?php

namespace Configurator;

/**
 * Container class to hold config options to reduce
 * the number of constants.
 * Maybe I should make it extend ArrayObject or
 * implement Iterator.  Not sure what the point
 * would be though...
 *
 * Should also make this a singleton.. Later though,
 * other deadlines to make
 **/
class Constants {
	protected $_constants;
	protected $_log;

	protected static $root = null;

	public static function getRoot() {
		if (self::$root === null) {
			self::$root = new Constants();
		}
		return self::$root;
	}

	public function __construct(\Monolog\Logger $log = null) {
		if (is_object($log)) {
			$this->setLog($log);
		} else {
			global $MLOG;
			if ($MLOG instanceof \Monolog\Logger) $this->setLog($MLOG);
		}
	}

	public function setLog(\Monolog\Logger $log) {
		$this->_log = $log;
	}

	public function getLog() {
		return $this->_log;
	}

	public function __get($name) {
		if (array_key_exists($name, $this->_constants)) {
			return $this->_constants[$name];
		} else {
			return null;
		}
	}
	public function __set($name, $val) {
		$this->_constants[$name] = $val;
	}

	public function setSecret($service, $name, $file, $throw=true) {
		if (is_readable($file)) {
			//$this->_log->addNotice("Loading $service -> $name secret");
			$this->_constants[$name] = trim(file_get_contents($file));
		} else {
			if ($throw)
				throw new ConfigException("Error! Secret file $file not found or not readable!");
			return;
		}
	}
}

class ConfigException extends \Exception {};

// vim: ts=3 sw=3 noet :
