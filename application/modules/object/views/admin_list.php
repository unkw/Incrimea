
<table id="objects-table" class="content-list">

    <thead class="select-all">
        <th><input type="checkbox" title="Выделить всё" /></th>
        <th>Заголовок</th>
        <th>Место отдыха</th>
        <th>Приоритет</th>
        <th>Цены от</th>
        <th>Дата</th>
        <th>Статус</th>
        <th>Просмотры</th>
        <th>Действия</th>
    </thead>

    <tbody>
        <?php foreach ($content as $c) : ?>
        <tr>
            <td class="selected"><input type="checkbox" /></td>
            <td class="title"><a href="<?php echo base_url().$c['alias'] ?>"><?php print $c['title']; ?></a></td>
            <td class="resort"><?php print $c['resort']; ?></td>
            <td class="priority"><?php print $c['priority']; ?></td>
            <td class="price"><?php print $c['price'].'$'; ?></td>
            <td class="created-date"><?php print date('d.m.Y', $c['created_date']); ?></td>
            <td class="status">
                <?php if ($c['published']): ?>
                <a href="#" class="on" title="Опубликовано"></a>
                <?php else: ?>
                <a href="#" class="off" title="Не опубликовано"></a>
                <?php endif; ?>
            </td>
            <td class="views">-</td>
            <td class="actions">
                <ul>
                    <li><?php echo anchor('admin/'.$module.'/edit/'.$c['id'], ' ', array('class'=>'edit', 'title'=>'Редактировать')) ?></li>
                    <li><?php echo anchor('admin/'.$module.'/delete/'.$c['id'], ' ', array('class'=>'trash', 'title'=>'Удалить')) ?></li>
                </ul>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>

    <tbody><tr><td class="pager" colspan="10"><?php print $pager; ?></td></tr></tbody>
</table>



