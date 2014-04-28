<?php
/* @var $this StudentsController */
/* @var $students array */


$this->breadcrumbs=array(
    'Студенты',
);

$this->menu=array(
    array('label'=>'Создать студента', 'url'=>array('create')),
    array('label'=>'Управление студентами', 'url'=>array('admin')),
    array('label'=>'Задание 1а', 'url'=>array('first')),
    array('label'=>'Задание 1б', 'url'=>array('second')),
    array('label'=>'Задание 1в', 'url'=>array('third')),
);

?>
<h1>Задание 1б</h1>
<p>
    Имена и средний балл студентов А, которые лайкнули студентов В,
    но при этом студенты В не поставили лайк ни на одной из страниц других студентов
</p>
<?php
$this->renderPartial('table', array('students' => $students,));
?>