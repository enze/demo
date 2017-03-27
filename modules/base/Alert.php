<?php
namespace demo\modules\base;

interface Alert {
	
	public function sendAlert($server, $taskId, $workerId, $data);
	
	public function isNeedAlert($sn, $createId, $dataId);
}