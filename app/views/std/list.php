<?php
/**
 * Standard mode default listing template 
 */
?>

<?php require APPROOT . '/views/std/inc/header.php'; ?>

<h2 id="nwTitle"><?php echo $data['title'];?></h2>

<span id="nwBack" class="float-left">
    <?php if(!empty($data['back'])): ?>
        <a href="<?php echo URLROOT . $data['back']['url'];?>">[ Back to: <?php echo $data['back']['title']; ?> ]</a>
    <?php endif; ?>
</span>

<span id="nwAPI" class="float-right">
    <a href="<?php echo $data['apiUrl'];?>">
        [ API ]
    </a>
</span>

<table id="nwTable" class="table">
<thead id="nwThead">
<tr>
    <?php foreach($data['columns'] as $column): ?>
        <th><?php echo sortTitle($column, $data);?></th>
    <?php endforeach; ?>
</tr>
</thead>

<tbody id="nwTbody">
<?php foreach($data['list'] as $row): ?>
<tr>
    <?php foreach($data['columns'] as $column): ?>
        <?php if(!empty($column['link'])): ?>
            <td>
                <?php 
                    echo "<a href='" . URLROOT . $column['link']['template'] 
                        . "/" . $row->{$column['link']['filter']} . "'>" 
                        . $row->{$column['field']} . "</a>";
                ?>
            </td>
        <?php else: ?>
        <td><?php echo $row->{$column['field']};?></td>
        <?php endif; ?>
    <?php endforeach; ?>
</tr>
<?php endforeach; ?>
</tbody>

</table>


<?php require APPROOT . '/views/std/inc/footer.php'; ?>