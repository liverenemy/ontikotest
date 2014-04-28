<?php
/* @var $this StudentsController */
/* @var $model Students */

$this->breadcrumbs=array(
	'Students'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Students', 'url'=>array('index')),
	array('label'=>'Create Students', 'url'=>array('create')),
	array('label'=>'Update Students', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Delete Students', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Students', 'url'=>array('admin')),
);
?>

<h1>View Students #<?php echo $model->ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'name',
		'grade',
	),
)); ?>
