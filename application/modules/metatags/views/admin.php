<!-- Фильтр -->
<?php echo form_open('', array('class' => 'admin-filters', 'method'=>'GET')); ?>
<?php echo form_close(); ?>

<!-- Список -->
<table id="metatags-table" class="content-list">

    <thead class="select-all">
        <th><input type="checkbox" title="Выделить все метатеги" /></th>
        <th>ID</th>
        <th>Адрес</th>
        <th>Заголовок</th>
        <th>Ключевые слова</th>
        <th>Описание</th>
        <th>Действия</th>
    </thead>
    
    <tbody>
        <?php foreach ($metatags as $m) : ?>
        <tr>
            <td><input type="checkbox" title="" /></td>
            <td><?php print $m['id']; ?></td>
            <td><?php print $m['path']; ?></td>
            <td><?php print $m['title']; ?></td>
            <td><?php print $m['keywords']; ?></td>
            <td><?php print $m['description']; ?></td>
            <td class="actions">
                <?php echo anchor('admin/metatags/edit/'.$m['id'], ' ', array('class'=>'edit', 'title'=>'Редактировать')) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>

    <tbody><tr><td class="pager" colspan="8"><?php print $pager; ?></td></tr></tbody>
</table>



