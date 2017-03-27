<?php
namespace demo\modules\alert;

use xb\swoole\Client;

use demo\modules\Base;
use demo\modules\base\Alert as InterfaceAlert;

use demo\modules\alert\traits\Alert as TraitsAlert;

class Quote extends Base implements InterfaceAlert {
	
	use TraitsAlert;
	
	public function sendAlert($server, $taskId, $workerId, $data) {
		$data = json_decode($data, true);
		$server->after($data['time'], function () use ($data) {
			return $this->run($data);
		});
	}
	
	public function isNeedAlert($sn, $createId, $dataId) {
		return true;
	}
	
	public function run($data) {
		
		if (false === $this->isNeedAlert($data['sn'], $data['createId'], $data['dataId'])) {
			return false;
		}
		
		$phone = $this->_getPhoneByCreateId($data['createId']);

		$message = [
			'type' => 'register',
			'phone' => 'xxxxx',
			'message' => '还有10分钟过期，可爱吧！！',
		];

		$message = json_encode($message);
		
		//for($i = 0; $i < 3; $i++) {
			$client = new Client('sms', ETC_ROOT . '/client.ini');
		
			$client->boot();
			if (!$client->isConnected()) {
				$client->conn();
			}
			$client->send($message);
			$client->close();
			//}
	}
}