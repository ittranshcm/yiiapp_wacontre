<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class RegisterForm extends CFormModel
{
	public $username;
	public $email;
	public $password;
	public $password_repeat;
	public $status;
	public $verifyCode;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('username, email, password, password_repeat', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
			//check duplicate email
			array('email', 'checkDuplicateEmail', 'email' => 'email'),
			//repeat password
			array('password_repeat', 'compare','compareAttribute'=>'password'),
			//inform: password not in database 
			array('password_repeat', 'safe'), 
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'verifyCode'=>'Verification Code',
		);
	}
	
	public function checkDuplicateEmail($attribute,$params){
		$data = Users::model()->count($attribute.'=:email', array(':email'=>$this->$params['email']));
		if((int)$data > 0){
			$this->addError($attribute, 'Email is not availble');
		}
	}
}