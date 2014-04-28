<?php
/**
 * Виджет списка последних новостей

 * @var array $news
 */

if (count($news))
{
    ?>
    <ul>
        <?php
        foreach ($news as $item)
        {
            ?>
            <li><?php echo CHtml::link($item->title, array('/news/view', 'id' => $item->id)); ?></li>
            <?php
        }
        ?>
    </ul>
    <p align="right">
        <?php
        echo CHtml::link('Все новости', array('/news/index'));
        ?>
    </p>
    <?php
}

?>