<?php
namespace demo\modules;

use Xb;
use xb\swoole\Server;

class Message {
	
	public $configFile = ETC_ROOT . '/message.ini';
	
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
		return $this->sendMessage($server, $taskId, $workerId, $data);
	}
	
	public function onFinish($server, $taskId, $data) {
		return 'finish';
	}
	
	public function sendMessage($server, $taskId, $workerId, $data) {
		$className = __NAMESPACE__ . '\\message\\' . ucfirst($this->service);
		$worker = Xb::createObject($className);
		$worker->config = $this->server->config;
		return call_user_func_array([$worker, 'sendMessage'], [$server, $taskId, $workerId, $data]);
	}
	
	public function run() {
		$this->server = Xb::createObject('\\xb\\swoole\\Server', true, $this->service, $this->configFile);
		$this->server->boot($this->callback);
	}
}