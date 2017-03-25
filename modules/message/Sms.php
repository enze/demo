<?php
namespace demo\modules\message;

class Sms {
	
	private $_property = [];
	
	public function sendMessage($server, $taskId, $workerId, $data) {
		$data = json_decode($data, true);

        $url = $this->config['sms']['sms.api'] . '?';
        $args = [
            'uid' => $this->config['sms']['sms.appId'],
            'pas' => $this->config['sms']['sms.key'],
            'mob' => $data['phone'],
            'cid' => $this->config['sms']['sms.' . $data['type']],
            'type' => 'json',
        ];
		
		$message = $data['message'];
        $param = explode('|', $message);
        $j = 1;
        for ($i = 0; $i < count($param); $i++) {
            $args['p' . $j] = $param[$i];
            $j++;
        }

        $url .= http_build_query($args);
		
		echo file_get_contents($url);
	}
	
	public function __get($name) {
		if (true === isset($this->_property[$name])) {
			return $this->_property[$name];
		}
		return null;
	}
	
	public function __set($name, $value) {
		if (true === isset($this->_property[$name])) {
			return null;
		}
		$this->_property[$name] = $value;
	}
}