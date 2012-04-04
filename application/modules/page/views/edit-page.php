
<?php if (validation_errors()): ?>
<div id="error_msg" class="message-box">
    <?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<?php echo form_open('', array('id' => 'edit-content')); ?>

<table><tbody><tr>

<td class="left-col">

    <div class="field-wrapper">
        <div><label class="title-label">Заголовок</label></div>
        <input type="text" name="edit-title" value="<?php echo set_value('edit-title', $content['title']) ?>"/>
    </div>

    <!-- Метатеги -->
    <?php echo $metatags; ?>

    <div class="field-wrapper">
        <div><label class="title-label">Настройки публикации</label></div>
        <label>
            <input type="checkbox" name="edit-status" value="1" <?php echo set_checkbox('edit-status', '1', $content['status'] ? TRUE : FALSE); ?> />
            Опубликовано
        </label>
        <label>
            <input type="checkbox" name="edit-sticky" value="1" <?php echo set_checkbox('edit-sticky', '1', $content['sticky'] ? TRUE : FALSE); ?>/>
            Закреплять вверху списка
        </label>
    </div>

    <?php print $alias; ?>

    <input type="submit" value="Сохранить" />
    <input type="button" value="Удалить" onclick="document.location = '<?php echo base_url(); ?>' + 'admin/page/delete/' + '<?php echo $content['id']; ?>'; return false;" />
    <a href="<?php echo base_url().'admin/page' ?>">Отмена</a>

</td>

<td class="right-col">

    <div class="field-wrapper">
        <div><label class="title-label">Текст страницы</label></div>
        <?php $this->ckeditor->editor('edit-body', html_entity_decode(set_value('edit-body', $content['body']))); ?>
    </div>

</td>

</tr></tbody></table>

<?php echo form_close(); ?>