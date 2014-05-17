<?php

class UsersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		$role = Yii::app()->user->getState('role');
		switch($role){
			case '1':
				$action = array('index', 'view', 'update', 'admin', 'delete');
				$rules = array('allow',  // allow all users to perform 'index' and 'view' actions
						'actions'=>$action,
						'users'=>array(Yii::app()->user->getState('email')),
					);
				break;
			case '2':
				$action = array('index', 'view', 'admin');
				$rules = array('allow',  // allow all users to perform 'index' and 'view' actions
						'actions'=>$action,
						'users'=>array(Yii::app()->user->getState('email')),
					);
				break;
			default:
				$rules = array();
		}
		return array(
			$rules,
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->renderPartial('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Users;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->email));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save()){
				$logs = new LogsInfo;
				$logs->setInformation('edit', array(
													'email' => Yii::app()->user->getState('email'),
													'uid' => $model->id,
													'uemail' => $model->email,
													'created_at' => date('m-d-Y H:i:s')
												));
				$message = $logs->getInformation();
				
				$logs->insertData(array(
									'log_type' =>LogsInfo::edit,
									'username' => Yii::app()->user->getState('username'),
									'user_id' => Yii::app()->user->getState('uid'),
									'infomation' => $message,
									'data' => $model->attributes,
				));
				$this->renderPartial('resupdate',array(
					'model'=>$model,
				));
			}
		}else{

			$this->renderPartial('update',array(
				'model'=>$model,
			));
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$model->delete();
		$logs = new LogsInfo;
			$logs->setInformation('authorize', array(
												'email' => Yii::app()->user->getState('email'),
												'route' => Yii::app()->getRequest()->getRequestUri(),
												'created_at' => date('m-d-Y H:i:s')
											));
			$message = $logs->getInformation();
			$logs = new LogsInfo;
			$logs->setInformation('delete', array(
												'email' => Yii::app()->user->getState('email'),
												'uid' => $model->id,
												'umail' => $model->email,
												'created_at' => date('Y-m-d H:i:s')
											));
			$message = $logs->getInformation();
			$logs->insertData(array(
								'log_type' =>LogsInfo::delete,
								'username' => Yii::app()->user->getState('username'),
								'user_id' => Yii::app()->user->getState('uid'),
								'infomation' => $message,
								'data' => ''
			));
			
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Users');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Users the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Users $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function filterAccessControl($filterChain)
	{
		$filter=new MyAccessControlFilter;  // CHANGED THIS
		$filter->setRules($this->accessRules());
		$filter->filter($filterChain);
	}
}
