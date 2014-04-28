<?php
/* @var $this StudentsController */
/* @var $dataProvider CActiveDataProvider */

$this->menu=array(
	array('label'=>'Создать студента', 'url'=>array('create')),
	array('label'=>'Управление студентами', 'url'=>array('admin')),
    array('label'=>'Задание 1а', 'url'=>array('first')),
    array('label'=>'Задание 1б', 'url'=>array('second')),
    array('label'=>'Задание 1в', 'url'=>array('third')),
);
?>

<h1>Студенты</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
