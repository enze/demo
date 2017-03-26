<?php
namespace demo\modules\base;

interface Message {
	
	public function sendMessage($server, $taskId, $workerId, $data);
}