<?php
/* @var $this SiteController */
/* @var $diffs Array */
/* @var $uploadImpossible Boolean */
$this->menu = array(
    array('label' => 'С клавиатуры',    'url' => array('arraysCompareByKeyboard')),
    array('label' => 'Из файла',        'url' => array('arraysCompareByFile')),
);
?>
<h1>Сравнение массивов из файла</h1>
<?php

    if (!empty($diffs))
    {
        $this->renderPartial('arrayDiff', array('diffs' => $diffs));
    }
?>

        <form method="post" enctype="multipart/form-data">
            <div class="row">
                <label for="arrayFile">
                    Создайте текстовый файл. Введите в этом файле первый массив чисел на первой строке,
                    второй массив - на второй строке. Массивы будут разбиты на элементы
                    <b>по символу пробела</b>.
                </label>
            </div>
            <div class="row">
                <input type="file" id="arrayFile" name="arrayFile">
            </div>
            <div class="row">
                <input type="submit">
            </div>
        </form>