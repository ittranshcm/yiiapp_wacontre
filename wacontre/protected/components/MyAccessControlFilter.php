<?php
/**
 * CAccessControlFilter class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CAccessControlFilter performs authorization checks for the specified actions.
 *
 * By enabling this filter, controller actions can be checked for access permissions.
 * When the user is not denied by one of the security rules or allowed by a rule explicitly,
 * he will be able to access the action.
 *
 * For maximum security consider adding
 * <pre>array('deny')</pre>
 * as a last rule in a list so all actions will be denied by default.
 *
 * To specify the access rules, set the {@link setRules rules} property, which should
 * be an array of the rules. Each rule is specified as an array of the following structure:
 * <pre>
 * array(
 *   'allow',  // or 'deny'
 * 
 *   // optional, list of action IDs (case insensitive) that this rule applies to
 *   // if not specified or empty, rule applies to all actions
 *   'actions'=>array('edit', 'delete'),
 * 
 *   // optional, list of controller IDs (case insensitive) that this rule applies to
 *   'controllers'=>array('post', 'admin/user'),
 * 
 *   // optional, list of usernames (case insensitive) that this rule applies to
 *   // Use * to represent all users, ? guest users, and @ authenticated users
 *   'users'=>array('thomas', 'kevin'),
 * 
 *   // optional, list of roles (case sensitive!) that this rule applies to.
 *   'roles'=>array('admin', 'editor'),
 * 
 *   // since version 1.1.11 you can pass parameters for RBAC bizRules
 *   'roles'=>array('updateTopic'=>array('topic'=>$topic))
 * 
 *   // optional, list of IP address/patterns that this rule applies to
 *   // e.g. 127.0.0.1, 127.0.0.*
 *   'ips'=>array('127.0.0.1'),
 * 
 *   // optional, list of request types (case insensitive) that this rule applies to
 *   'verbs'=>array('GET', 'POST'),
 * 
 *   // optional, a PHP expression whose value indicates whether this rule applies
 *   // The PHP expression will be evaluated using {@link evaluateExpression}.
 *   // A PHP expression can be any PHP code that has a value. To learn more about what an expression is,
 *   // please refer to the {@link http://www.php.net/manual/en/language.expressions.php php manual}.
 *   'expression'=>'!$user->isGuest && $user->level==2',
 * 
 *   // optional, the customized error message to be displayed
 *   // This option is available since version 1.1.1.
 *   'message'=>'Access Denied.',
 * 
 *   // optional, the denied method callback name, that will be called once the
 *   // access is denied, instead of showing the customized error message. It can also be
 *   // a valid PHP callback, including class method name (array(ClassName/Object, MethodName)),
 *   // or anonymous function (PHP 5.3.0+). The function/method signature should be as follows:
 *   // function foo($user, $rule) { ... }
 *   // where $user is the current application user object and $rule is this access rule.
 *   // This option is available since version 1.1.11.
 *   'deniedCallback'=>'redirectToDeniedMethod',
  * )
 * </pre>
 *
 * @property array $rules List of access rules.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.web.auth
 * @since 1.0
 */
class MyAccessControlFilter extends CAccessControlFilter
{
	protected function resolveErrorMessage($rule)
	{	
		if($rule->allow == false){
			$logs = new LogsInfo;
			$logs->setInformation('authorize', array(
												'email' => Yii::app()->user->getState('email'),
												'route' => Yii::app()->getRequest()->getRequestUri(),
												'created_at' => date('m-d-Y H:i:s')
											));
			$message = $logs->getInformation();
			
			$logs->insertData(array(
								'log_type' =>LogsInfo::authorize,
								'username' => Yii::app()->user->getState('username'),
								'user_id' => Yii::app()->user->getState('uid'),
								'infomation' => $message,
								'data' => '',
			));
		}
		
		if($rule->message!==null)
			return $rule->message;
		elseif($this->message!==null)
			return $this->message;
		else
			return Yii::t('yii','You are not authorized to perform this action2.');
	}
}
