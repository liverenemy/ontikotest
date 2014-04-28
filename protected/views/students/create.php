<?php
/* @var $this StudentsController */
/* @var $model Students */

$this->breadcrumbs=array(
	'Students'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список записей Students', 'url'=>array('index')),
	array('label'=>'Управление записями Students', 'url'=>array('admin')),
);
?>

<h1>Создать Students</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>