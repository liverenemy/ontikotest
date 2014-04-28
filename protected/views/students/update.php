<?php
/* @var $this StudentsController */
/* @var $model Students */

$this->breadcrumbs=array(
	'Students'=>array('index'),
	$model->name=>array('view','id'=>$model->ID),
	'Update',
);

$this->menu=array(
	array('label'=>'Список записей Students', 'url'=>array('index')),
	array('label'=>'Создать запись Students', 'url'=>array('create')),
	array('label'=>'Просмотреть запись Students', 'url'=>array('view', 'id'=>$model->ID)),
	array('label'=>'Управление записями Students', 'url'=>array('admin')),
);
?>

<h1>Редактирование Students <?php echo $model->ID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>