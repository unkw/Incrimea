
<table id="articles-table" class="content-list">

    <thead>
        <th><input type="checkbox" title="Выделить всё" /></th>
        <th>Заголовок</th>
        <th>Дата</th>
        <th>Статус</th>
        <th>Просмотры</th>
        <th>Действия</th>
    </thead>

    <tbody>
        <?php foreach ($content as $c) : ?>
        <tr>
            <td class="select-all"><input type="checkbox" title="Выделить все страницы" /></td>
            <td class="title"><a href="<?php echo base_url().$c['alias']; ?>"><?php print $c['title']; ?></a></td>
            <td class="created-date"><?php print date('d.m.Y', $c['created_date']); ?></td>
            <td class="status">
                <?php if ($c['status']): ?>
                <a href="#" class="on" title="Опубликовано"></a>
                <?php else: ?>
                <a href="#" class="off" title="Не опубликовано"></a>
                <?php endif; ?>
            </td>
            <td class="views">-</td>
            <td class="actions">
                <?php echo anchor('admin/article/edit/'.$c['id'], ' ', array('class'=>'edit', 'title'=>'Редактировать')) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>

    <tbody><tr><td class="pager" colspan="6"><?php print $pager; ?></td></tr></tbody>
</table>

<script type="text/javascript">

    $(function(){
        
        $('td.status').children().click(function(){

            trace('ajax');
        });
    });

</script>


