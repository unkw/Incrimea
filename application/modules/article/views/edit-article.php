
<?php if (validation_errors() || isset($errors)): ?>

<div id="error_msg" class="message-box">
    <?php echo validation_errors(); ?>
    <?php if (isset($errors)) print $errors; ?>
</div>

<?php endif; ?>

<?php echo form_open_multipart('', array('id' => 'edit-content')); ?>

<input type="hidden" name="edit-id" value="<?php echo $content['id']; ?>" />

<table><tbody><tr>

<td class="left-col">

    <div>
        <div><label class="title-label">Заголовок</label></div>
        <input type="text" name="edit-title" value="<?php echo set_value('edit-title', $content['title']) ?>"/>
    </div>

    <div>
        <div><label class="title-label">Титульное изображение</label></div>

        <input type="file" name="upload-image" />

        <div id="img-container">
            <a href="#" id="img-remove"></a>
            <img id="img-main" src="<?php echo base_url().'images/article/thumb/'.set_value('edit-image', $content['image_src']); ?>" alt=""/>
        </div>

        <div>
            <input type="button" id="img-upload" value="Загрузить" />
            <span id="img-msg"></span>
        </div>

        <p><label>Описание</label></p>
        <input type="text" name="edit-image-desc" value="<?php echo set_value('edit-image-desc', $content['image_desc']) ?>"/>
        <input type="hidden" name="edit-image" value="<?php echo set_value('edit-image', $content['image_src']); ?>"/>

    </div>

    <!-- Метатеги -->
    <?php print $metatags; ?>

    <div>
        <div><label class="title-label">Место отдыха</label></div>
        <select name="edit-resorts">
        <option value="0"></option>
        <?php foreach ($resorts as $r) : ?>
        <option value="<?php echo $r['id'] ?>" <?php echo set_select('edit-resorts', $r['id'], $r['id'] == $content['resort_id'] ? TRUE : FALSE); ?> ><?php echo $r['name'] ?></option>
        <?php endforeach; ?>
        </select>
    </div>

    <div>
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

    <input type="hidden" name="module-name" value="article">

    <input type="submit" value="Сохранить" />
    <input type="button" value="Применить" />
    <a href="<?php echo base_url().'admin/article' ?>">Отмена</a>

</td>

<td class="right-col">

    <div class="field-wrapper">
        <div><label class="title-label">Текст страницы</label></div>
        <?php $this->ckeditor->editor('edit-body', html_entity_decode(set_value('edit-body', $content['body']))); ?>
    </div>

</td>

</tr></tbody></table>

<?php echo form_close(); ?>