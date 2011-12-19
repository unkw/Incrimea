
<?php if (validation_errors() || isset($errors)): ?>

<div id="error_msg" class="message-box">
    <?php echo validation_errors(); ?>
    <?php if (isset($errors)) print $errors; ?>
</div>

<?php endif; ?>

<?php echo form_open_multipart('', array('id' => 'edit-content')); ?>

<input type="hidden" name="edit-id" value="<?php echo $content['id']; ?>" />

<div>
    <div><label>Заголовок</label></div>
    <input type="text" name="edit-title" size="50" value="<?php echo set_value('edit-title', $content['title']) ?>"/>
</div>

<div>
    <div><label>Место отдыха</label></div>
    <select name="edit-resorts">
    <option value="0"></option>
    <?php foreach ($resorts as $r) : ?>
    <option value="<?php echo $r['id'] ?>" <?php echo set_select('edit-resorts', $r['id'], $r['id'] == $content['resort_id'] ? TRUE : FALSE); ?> ><?php echo $r['name'] ?></option>
    <?php endforeach; ?>
    </select>
</div>


<div>
    <div><label>Титульное изображение</label></div>

    <a href="#" id="img-remove">Удалить</a>

    <input type="file" name="upload-image" />

    <div id="img-container">
        <img id="img-main" src="<?php echo base_url() . set_value('edit-thumb', $content['image_thumb']); ?>" alt=""/>
    </div>
    <div>
        <input type="button" id="img-upload" value="Загрузить" />
        <span id="img-msg"></span>
    </div>

    <label>Описание</label>
    <input type="text" name="edit-image-desc" size="30" value="<?php echo set_value('edit-image-desc', $content['image_desc']) ?>"/>
    <input type="hidden" name="edit-image" value="<?php echo set_value('edit-image', $content['image_src']); ?>"/>
    <input type="hidden" name="edit-thumb" value="<?php echo set_value('edit-thumb', $content['image_thumb']); ?>"/>
</div>

<div>
    <div><label>Дата начала</label></div>
    <input type="text" name="edit-date-start" class="edit-event-dates" value="<?php echo set_value('edit-date-start', $content['date_start']); ?>" required/>
</div>

<div>
    <div><label>Дата завершения</label></div>
    <input type="text" name="edit-date-end" class="edit-event-dates" value="<?php echo set_value('edit-date-end', $content['date_end']); ?>" required/>
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

<input type="hidden" name="module-name" value="event">

<input type="submit" value="Сохранить" />

<?php echo form_close(); ?>