<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class LogsInfo
{
	private $deny = "User '%s' are not authorized to perform %s at %s";
	private $edit = "User %s edited id %d(%s) at %s";
	private $delete = "User %s deleted id %d(%s) at %s";
	private $message = null;
	
	const deny = 'authorize';
	const edit = 'edit';
	const delete = 'delete';

	public function setInformation($type, $data){
		switch($type){
			case 'authorize':
				$this->setAuthorizeMsg($data);
				break;
			case 'edit':
				$this->setEditMsg($data);
				break;
			case 'delete':
				$this->setDeleteMsg($data);
				break;
		}
	}
	
	private function setAuthorizeMsg($data){
		$this->message = sprintf($this->deny, $data['email'], $data['route'], $data['created_at']);
	}
	private function setEditMsg($data){
		$this->message = sprintf($this->edit, $data['email'], $data['uid'], $data['uemail'], $data['created_at']);
	}
	private function setDeleteMsg($data){
		$this->message = sprintf($this->delete, $data['email'], $data['uid'], $data['umail'],$data['created_at']);
	}
	
	public function getInformation(){
		return $this->message;
	}
	
	public function insertData($data){
		$log_model = new Logs;
		$log_model->log_type = $data['log_type'];
		$log_model->username = $data['username'];
		$log_model->user_id = $data['user_id'];
		$log_model->created_at = date('y-m-d H:i:s');
		$log_model->infomation = $data['infomation'];
		$log_model->data = print_r($data['data'], true);
		$log_model->save();
	}
}