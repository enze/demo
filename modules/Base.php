<?php
namespace demo\modules;

class Base {
	protected $_proporty = [];

	
	public function __get($name) {
		if (true === isset($this->_proporty[$name])) {
			return $this->_proporty[$name];
		}
		return null;
	}
	
	public function __set($name, $value) {
		if (true === isset($this->_proporty[$name])) {
			return null;
		}
		$this->_proporty[$name] = $value;
	}
}