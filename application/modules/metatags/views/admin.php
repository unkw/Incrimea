<!-- Подменю -->
<ul id="submenu">
    <li><a href="<?php echo base_url() .'admin/metatags/new'; ?>">Создать метатег для произвольной страницы</a></li>
</ul>

<!-- Фильтр -->
<?php echo form_open('', array('class' => 'admin-filters', 'method'=>'GET')); ?>
<label>Фильтр:
<select name="list">
    <option value="all">Все</option>
    <option value="custom" <?php if ($params['list'] == 'custom') echo 'selected="selected"'; ?>>Метатеги произвольных страниц</option>
</select>
</label>
<?php echo form_close(); ?>

<!-- Список -->
<table>

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
            <td><a href="<?php print base_url() . 'admin/metatags/edit/' . $m['id']; ?>">Изменить</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>

    <tbody><tr><td class="pager" colspan="8"><?php print $pager; ?></td></tr></tbody>
</table>



