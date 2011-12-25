
<?php if (validation_errors() || isset($errors)): ?>

<div id="error_msg" class="message-box">
    <?php echo validation_errors(); ?>
    <?php if (isset($errors)) print $errors; ?>
</div>

<?php endif; ?>

<?php echo form_open_multipart('', array('id' => 'edit-content')); ?>

<input type="hidden" name="edit-id" value="<?php echo $content['id']; ?>" />

<div>
    <div><label>Название</label></div>
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
    <div><label>Тип</label></div>
    <select name="edit-types">
    <?php foreach ($types as $t) : ?>
        <option value="<?php echo $t['id'];?>" <?php echo set_select('edit-types', $t['id'], $t['id'] == $content['type_id'] ? TRUE : FALSE); ?>>
            <?php echo $t['name'];?>
        </option>
    <?php endforeach; ?>
    </select>
</div>

<div>
    <div><label>Галерея объекта</label></div>
    <input type="file" name="edit-gallery" multiple="true" />
    <ul id="edit-gallery">
        <?php foreach ($content['images'] as $img): ?>
        <li>
            <div>
                <img src="<?php echo base_url().'images/object/thumb/'.$img; ?>" alt="" />
                <input type="hidden" name="edit-img[]" value="<?php echo $img; ?>" />
            </div>
            <progress value="100"></progress>
            <a href="" class="remove">Удалить</a>
        </li>
        <?php endforeach; ?>
    </ul>
    <div class="clear"></div>
    <input type="button" id="upload-submit" value="Загрузить изображения">
</div>

<div>
    <div><label>Цены от ($)</label></div>
    <input type="text" name="edit-min-price" size="5" value="<?php echo set_value('edit-min-price', $content['min_price']) ?>"/>
</div>

<div>
    <div><label>Питание</label></div>
    <input type="text" name="edit-food" size="80" value="<?php echo set_value('edit-food', $content['food']) ?>"/>
</div>

<div>
    <div><label>До пляжа</label></div>
    <input type="text" name="edit-beach" size="80" value="<?php echo set_value('edit-beach', $content['beach']) ?>"/>
</div>

<div>
    <div><label>Номерной фонд</label></div>
    <textarea name="edit-number-fund" cols="65" rows="4"><?php echo set_value('edit-number-fund', $content['number_fund']); ?></textarea>
</div>

<div id="edit-structure-wrap">
    <div><label>Инфраструктура</label></div>
    <?php foreach ($structure as $str) : ?>
        <div>
            <label>
                <input type="checkbox" name="edit-structure[]" value="<?php echo $str['url_name'];?>"
                    <?php echo set_checkbox('edit-structure[]', $str['url_name'], in_array($str['url_name'], $content['structure']) ? TRUE : FALSE); ?>
                />
                <?php echo $str['name']; ?>
            </label>
        </div>
    <?php endforeach; ?>
</div>

<div>
    <div><label>Полное описание</label></div>
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