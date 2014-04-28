<?php
/* @var $this NewsController */
/* @var $dataProvider CActiveDataProvider */

$this->menu=array(
    array('label'=>'Список новостей', 'url'=>array('index')),
    array('label'=>'Добавить новость', 'url'=>array('create')),
);
?>

<h1>Новости</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
