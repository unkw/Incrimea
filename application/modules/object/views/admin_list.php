<ul id="submenu">
    <li><a href="<?php echo base_url() .'admin/'.$module.'/new'; ?>">Создать объект</a></li>
    <li><a href="<?php echo base_url() .'admin/'.$module.'/fields'; ?>">Управление полями</a></li>
</ul>

<table>

    <thead class="select-all">
        <th><input type="checkbox" title="Выделить всё" /></th>
        <th>Заголовок</th>
        <th>Автор</th>
        <th>Дата создания</th>
        <th>Публикация</th>
        <th>Действия</th>
    </thead>

    <tbody>
        <?php foreach ($content as $c) : ?>
        <tr>
            <td><input type="checkbox" title="Выделить все страницы" /></td>
            <td><a href="<?php echo base_url().$c['alias'] ?>"><?php print $c['title']; ?></a></td>
            <td><?php print $c['username']; ?></td>
            <td><?php print date('Y-m-d H:i:s', $c['created_date']); ?></td>
            <td><?php print $c['published'] ? 'Опубликовано' : 'Не опубликовано'; ?></td>
            <td><a href="<?php print base_url() . 'admin/'.$module.'/edit/' . $c['id']; ?>">Изменить</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>

    <tbody><tr><td class="pager" colspan="6"><?php print $pager; ?></td></tr></tbody>
</table>



