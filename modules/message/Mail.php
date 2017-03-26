<?php
namespace demo\modules\message;

use demo\modules\Base;
use demo\modules\base\Message as InterfaceMessage;

class Mail extends Base implements InterfaceMessage {
	
	public function sendMessage($server, $taskId, $workerId, $data) {
		$data = json_decode($data, true);
		
		$transport = \Swift_SmtpTransport::newInstance($this->config['mail']['mail.host'], $this->config['mail']['mail.port'], 1 == $this->config['mail']['mail.ssl'] ? 'ssl' : '');
		$transport->setUsername($this->config['mail']['mail.username']);
		$transport->setPassword($this->config['mail']['mail.password']);

		$mailer = \Swift_Mailer::newInstance($transport);

		$message = \Swift_Message::newInstance();
		$message->setFrom([$this->config['mail']['mail.username'] => $this->config['mail']['mail.from']]);
		$message->setTo([$data['mail'] => $data['user']]);
		$message->setSubject($data['title']);
		$message->setBody($data['message'], 'text/html', $this->config['mail']['mail.charset']);
		
		try {
			$mailer->send($message);
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}
}