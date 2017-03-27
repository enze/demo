<?php
namespace demo\modules;

use Xb;
use xb\swoole\Server;

class Alert {
	
	public $configFile = ETC_ROOT . '/alert.ini';
	
	public $service = false;
	
	public $server = null;
	
	public $callback = [];
	
	public function __construct() {
		$this->callback = [
			[
				'event' => 'receive',
				'callback' => [$this, 'onReceive'],
			],
			[
				'event' => 'task',
				'callback' => [$this, 'onTask'],
			],
			[
				'event' => 'finish',
				'callback' => [$this, 'onFinish'],
			],
		];
	}

	public function onReceive($server, $fd, $fromId, $data) {
		$server->task($data);
		$server->close($fd);
	}
	
	public function onTask($server, $taskId, $workerId, $data) {
		$className = __NAMESPACE__ . '\\alert\\' . ucfirst($this->service);
		$worker = Xb::createObject($className);
		$worker->config = $this->server->config;
		return call_user_func_array([$worker, 'sendAlert'], [$server, $taskId, $workerId, $data]);
	}
	
	public function onFinish($server, $taskId, $data) {
		return 'finish';
	}
	
	public function run() {
		$this->server = Xb::createObject('\\xb\\swoole\\Server', true, $this->service, $this->configFile);
		$this->server->boot($this->callback);
	}
}