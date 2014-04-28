<?php
/* @var $this NewsController */
/* @var $model News */

$this->menu=array(
    array('label'=>'Список новостей', 'url'=>array('index')),
    array('label'=>'Добавить новость', 'url'=>array('create')),
);
?>

<h1>Добавить новость</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>