<?php
/* @var $this SiteController */
/* @var $diffs Array */
$this->menu = array(
    array('label' => 'С клавиатуры',    'url' => array('arraysCompareByKeyboard')),
    array('label' => 'Из файла',        'url' => array('arraysCompareByFile')),
);
?>
<h1>Сравнение массивов с клавиатуры</h1>
<?php
if (!empty($diffs))
{
    $this->renderPartial('arrayDiff', array('diffs' => $diffs));
}
?>
<form method="post">
    <label for="arrayString">
        Введите первый массив чисел на первой строке, второй массив - на второй строке. Массивы будут разбиты на элементы
        <b>по символу пробела</b>.
    </label>
    <textarea id="arrayString" name="arrayString" style="width: 90%;"><?php
        if (!empty($_POST['arrayString'])) echo $_POST['arrayString'];
        ?></textarea>
    <input type="submit">
</form>