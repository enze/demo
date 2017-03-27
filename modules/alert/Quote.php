<?php
namespace demo\modules\alert;

use demo\modules\Base;
use demo\modules\base\Alert as InterfaceAlert;

use demo\modules\alert\traits\Alert as TraitsAlert;

class Quote extends Base implements InterfaceAlert {
	
	use TraitsAlert;
	
	public function sendAlert($server, $taskId, $workerId, $data) {
		$data = json_decode($data, true);
		
		echo time();
		echo "\n";
		$server->after($data['time'], function () use ($data) {
			echo time();
			echo "\n";
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

		$data = [
			'type' => 'notify',
			'phone' => 'xxxxxx',
			'message' => '还有10分钟过期，共1',
		];

		$message = json_encode($data);

		/*
		 * 创建UDP连接
		 */
		$fp = stream_socket_client('tcp://127.0.0.1:8223', $errno, $errstr);
		if (!$fp) {
			echo "ERROR: $errno - $errstr<br />\n";
		} else {
			/*
			 * 简单发送日志信息
			 */
			flock($fp, LOCK_EX);
			fwrite($fp, $message);
			flock($fp, LOCK_UN);
			fclose($fp);
		}
	}
}