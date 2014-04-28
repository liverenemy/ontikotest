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
<h1>Задание 1в</h1>
<p>
    Имена и средний балл студентов А, которые не лайкали чужие страницы и не были лайкнуты другими пользователями.
</p>
<?php
$this->renderPartial('table', array('students' => $students,));
?>