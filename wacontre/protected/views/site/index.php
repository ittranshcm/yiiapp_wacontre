<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>
<?php
if(!Yii::app()->user->isGuest){
	echo "<a href='/index.php?r=users'>Manage user</a>";
}
?>
<br />
<?php
if(Yii::app()->user->getState('role') == 1){
	echo '<a href="/index.php?r=logs">Logs</a>';
}
?>
