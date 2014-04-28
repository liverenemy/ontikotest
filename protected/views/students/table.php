<?php
/** @var StudentsController $this */
/** @var array              $students */
?>

<table>
    <tr>
        <th>Имя</th>
        <th>Средний балл</th>
    </tr>
    <?php
    foreach ($students as $student)
    {
        ?>
        <tr>
            <td><?php echo $student['name']; ?></td>
            <td><?php echo $student['grade']; ?></td>
        </tr>
    <?php
    }
    ?>
</table>