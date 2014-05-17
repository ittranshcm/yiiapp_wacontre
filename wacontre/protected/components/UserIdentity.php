<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		//$users = array('admin' => 'admin');
		$users = Users::model()->findByAttributes(array('email'=>$this->username, 'password' => sha1($this->password)));
		$uid = $users->id;
		$role = UserRoles::model()->findByAttributes(array('uid'=>$uid));
		$role_id = $role->rid;
		$this->setState('role', $role_id);
		$this->setState('email', $users->email);
		$this->setState('username', $users->username);
		$this->setState('uid', $users->id);
		if(!isset($users->username))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users->password !== sha1($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}
}