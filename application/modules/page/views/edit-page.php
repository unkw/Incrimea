
<?php if (validation_errors()): ?>
<div id="error_msg" class="message-box">
    <?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<?php echo form_open('', array('id' => 'edit-content')); ?>

<div>
    <div><label>Заголовок</label></div>
    <input type="text" name="edit-title" value="<?php echo set_value('edit-title', $content['title']) ?>"/>
</div>




<div>
    <div><label>Текст страницы</label></div>
    <?php $this->ckeditor->editor('edit-body', html_entity_decode(set_value('edit-body', $content['body']))); ?>
</div>

<div>
    <div><label>Настройки публикации</label></div>
    <label>
        <input type="checkbox" name="edit-status" value="1" <?php echo set_checkbox('edit-status', '1', $content['status'] ? TRUE : FALSE); ?> />
        Опубликовано
    </label>
    <label>
        <input type="checkbox" name="edit-sticky" value="1" <?php echo set_checkbox('edit-sticky', '1', $content['sticky'] ? TRUE : FALSE); ?>/>
        Закреплять вверху списка
    </label>
</div>

<input type="submit" value="Сохранить" />

<?php echo form_close(); ?>