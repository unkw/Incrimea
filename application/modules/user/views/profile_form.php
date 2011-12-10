
<?php print form_open('', array('id' => 'user-edit-form')); ?>

<!-- Почтовый ящик -->
<div id="user-edit-email" class="form-item">
    <div><label>Почтовый адрес (для входа на сайт): <span class="red">*</span></label></div>
    <input type="text" name="edit-email" class="form-text" 
           value="<?php print set_value('edit-email', $user['email']); ?>"/>
    <?php print form_error('edit-email'); ?>
</div>

<?php if (!$user['id']): ?>

<div id="user-edit-password" class="form-item">
    <div><label>Пароль: <span class="red">*</span></label></div>
    <input type="password" name="edit-pass" class="form-text"/>
    <?php print form_error('edit-pass'); ?>
</div>

<div class="form-item">
    <div><label>Подтверждение пароля: <span class="red">*</span></label></div>
    <input type="password" name="edit-passconf" class="form-text"/>
    <?php print form_error('edit-passconf'); ?>
</div>

<?php endif; ?>

<div id="user-edit-name" class="form-item">
    <div><label>Имя пользователя: <span class="red">*</span></label></div>
    <input type="text" name="edit-name" class="form-text" value="<?php print set_value('edit-name', $user['username']); ?>"/>
    <?php print form_error('edit-name'); ?>
</div>

<div id="user-edit-role" class="form-item">
    <div><label>Роль: <span class="red">*</span></label></div>
    <select name="edit-role">
        <?php foreach ($roles as $r): ?>
            <option value="<?php print $r['id']; ?>"
                <?php print set_select('edit-role', $r['id'], $r['id'] == $user['role_id'] ? TRUE : FALSE); ?> >
            <?php print $r['name']; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php print form_error('edit-role'); ?>
</div>

<div id="user-edit-active" class="form-item">
    <div><label>Статус: <span class="red">*</span></label></div>
    <div>
        <label>
            <input type="radio" name="edit-status" value="1" class="form-radio"
                   <?php echo set_radio('edit-status', '1', TRUE); ?> />
            Активен
        </label>
    </div>
    <div>
        <label>
            <input type="radio" name="edit-status" value="0" class="form-radio"
                   <?php echo set_radio('edit-status', '0', !$user['active'] ? TRUE : FALSE); ?> />
            Забанен
        </label>
    </div>
    <?php print form_error('edit-status'); ?>
</div>

<?php if ($user['id']): ?>
    <input type="hidden" name="user-id" value="<?php echo $user['id']; ?>" />
    <input type="submit" id="user-edit-submit" value="Сохранить" />
    <a href="<?php echo base_url() . 'admin/user/delete/' . $user['id']; ?>" title="Удалить пользователя">Удалить</a>
<?php else: ?>
    <input type="submit" id="user-edit-submit" value="Добавить" />
<?php endif; ?>

<?php print form_close(); ?>