<table>

    <thead class="select-all">
        <th><input type="checkbox" title="Выделить всех пользователей" /></th>
        <th>Имя</th>
        <th>Почтовый ящик</th>
        <th>Роль</th>
        <th>Последний доступ</th>
        <th>Дата регистрации</th>
        <th>Статус</th>
        <th>Действия</th>
    </thead>
    
    <tbody>
        <?php foreach ($users as $user) : ?>
        <tr>
            <td><input type="checkbox" title="Выделить всех пользователей" /></td>
            <td><?php print $user['username']; ?></td>
            <td><?php print $user['email']; ?></td>
            <td><?php print $user['role']; ?></td>
            <td><?php if ($user['last_login']) print date('Y-m-d H:i:s', $user['last_login']); ?></td>
            <td><?php print date('Y-m-d H:i:s', $user['created_date']); ?></td>
            <td><?php print $user['active'] ? 'Активен' : 'Забанен'; ?></td>
            <td><a href="<?php print base_url() . 'admin/user/edit/' . $user['id']; ?>">Изменить</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>

    <tbody><tr><td class="pager" colspan="8"><?php print $pager; ?></td></tr></tbody>
</table>



