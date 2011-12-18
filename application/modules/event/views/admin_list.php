
<a href="<?php echo base_url() .'admin/'.$module.'/new'; ?>">Создать событие</a>

<table>

    <thead class="select-all">
        <th><input type="checkbox" title="Выделить всё" /></th>
        <th>Заголовок</th>
        <th>Автор</th>
        <th>Дата создания</th>
        <th>Статус</th>
        <th>Действия</th>
    </thead>

    <tbody>
        <?php foreach ($content as $c) : ?>
        <tr>
            <td><input type="checkbox" title="Выделить все страницы" /></td>
            <td><a href="<?php echo base_url() ?>event/view/<?php echo $c['id'] ?>"><?php print $c['title']; ?></a></td>
            <td><?php print $c['username']; ?></td>
            <td><?php print date('Y-m-d H:i:s', $c['created_date']); ?></td>
            <td><?php print $c['status'] ? 'Опубликовано' : 'Не опубликовано'; ?></td>
            <td><a href="<?php print base_url() . 'admin/'.$module.'/edit/' . $c['id']; ?>">Изменить</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>

    <tbody><tr><td class="pager" colspan="6"><?php print $pager; ?></td></tr></tbody>
</table>



