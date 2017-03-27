<?php
namespace demo\modules\message;

use demo\modules\Base;
use demo\modules\base\Message as InterfaceMessage;

use xb\mailer\Mailer;

class Mail extends Base implements InterfaceMessage {
	
	public function sendMessage($server, $taskId, $workerId, $data) {
		$data = json_decode($data, true);
		
		$mailer = new Mailer;
		$mailer->smtp = $this->config['mail']['mail.host'];
		$mailer->port = $this->config['mail']['mail.port'];
		$mailer->ssl = 1 == $this->config['mail']['mail.ssl'] ? true : false;

		$mailer->username = $this->config['mail']['mail.username'];
		$mailer->password = $this->config['mail']['mail.password'];

		$mailer->charset = $this->config['mail']['mail.charset'];
		$mailer->mailFrom = [$this->config['mail']['mail.username'] => $this->config['mail']['mail.from']];
		$mailer->mailTo = [$data['mail'] => $data['user']];
		$mailer->title = $data['title'];
		$mailer->message = $data['message'];
		
		try {
			$mailer->send();
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}
}