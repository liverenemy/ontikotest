<?php
/* @var $this SiteController */
/* @var $diffs Array */
if (!empty($diffs))
{
    ?>
    <ol style="list-style-type: lower-latin;">
        <?php
        if (!empty($diffs['a']))
        {
            ?>
            <li>Массив [а] не содержит следующие элементы массива [б]: <?php echo implode(", ", $diffs['a']); ?>.</li>
        <?php
        }
        else
        {
            ?>
            <li>Массив [а] содержит все элементы массива [б].</li>
        <?php
        }
        if (!empty($diffs['b']))
        {
            ?>
            <li>Массив [б] не содержит следующие элементы массива [а]: <?php echo implode(", ", $diffs['b']); ?>.</li>
        <?php
        }
        else
        {
            ?>
            <li>Массив [б] содержит все элементы массива [а].</li>
        <?php
        }
        ?>
    </ol>
<?php
}