<?php
/* @var $this NewsController */
/* @var $model News */

$this->menu=array(
	array('label'=>'Список новостей', 'url'=>array('index')),
	array('label'=>'Добавить новость', 'url'=>array('create')),
	array('label'=>'Просмотреть новость', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Управление новостями', 'url'=>array('admin')),
);
?>

<h1>Редактирование новости <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>