<?php
namespace demo;

class Application {
	
	public $module = '';
	
	public $default = '';
	
	public function run() {
		if (true === empty($this->module)) {
			$this->module = $this->default;
		}
		
		$this->module = ucfirst($this->module);
		
		$moduleName = 'demo\\modules\\' . $this->module;
		$module = new $moduleName;
		
		$module->run();
	}
}