
<?php if (validation_errors() || isset($errors)): ?>

<div id="error_msg" class="message-box">
    <?php echo validation_errors(); ?>
    <?php if (isset($errors)) print $errors; ?>
</div>

<?php endif; ?>

<?php echo form_open_multipart('', array('id' => 'edit-content')); ?>

<!-- ID контента  -->
<input type="hidden" name="edit-id" value="<?php echo $obj['id']; ?>" />

<!-- Название  -->
<div>
    <div><label class="title-label">Название</label></div>
    <input type="text" name="edit-title" size="50" value="<?php echo set_value('edit-title', $obj['title']) ?>"/>
</div>

<!-- Метатеги -->
<?php print $metatags; ?>

<!-- Месторасположение -->
<div>
    <div><label class="title-label">Месторасположение</label></div>
    <textarea name="edit-location" rows="2" cols="50" style="width: 100%;"><?php echo set_value('edit-location', $obj['location']) ?></textarea>
</div>

<!-- Место отдыха -->
<div>
    <div><label class="title-label">Место отдыха</label></div>
    <select name="edit-resorts">
    <option value="0"></option>
    <?php foreach ($resorts as $r) : ?>
    <option value="<?php echo $r['id'] ?>" <?php echo set_select('edit-resorts', $r['id'], $r['id'] == $obj['resort_id'] ? TRUE : FALSE); ?> ><?php echo $r['name'] ?></option>
    <?php endforeach; ?>
    </select>
</div>

<!-- Регион -->
<div>
    <div><label class="title-label">Регион</label></div>
    <select name="edit-region">
    <option value="0"></option>
    <?php foreach ($regions as $reg) : ?>
    <option value="<?php echo $reg['id'] ?>" <?php echo set_select('edit-region', $reg['id'], $reg['id'] == $obj['region_id'] ? TRUE : FALSE); ?> ><?php echo $reg['name'] ?></option>
    <?php endforeach; ?>
    </select>
</div>

<!-- Тип -->
<div>
    <div><label class="title-label">Тип</label></div>
    <select name="edit-types">
    <?php foreach ($types as $t) : ?>
        <option value="<?php echo $t['id'];?>" <?php echo set_select('edit-types', $t['id'], $t['id'] == $obj['type_id'] ? TRUE : FALSE); ?>>
            <?php echo $t['name'];?>
        </option>
    <?php endforeach; ?>
    </select>
</div>

<!-- Галерея объекта -->
<div>
    <div><label class="title-label">Галерея объекта</label></div>
    <input type="file" name="edit-gallery" multiple="true" />
    <ul id="edit-gallery">
        <?php foreach ($obj['images'] as $img): ?>
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

<!-- Цена -->
<div>
    <div><label class="title-label">Цены от ($)</label></div>
    <input type="text" name="edit-price" size="5" value="<?php echo set_value('edit-price', $obj['price']) ?>"/>
</div>

<!-- Питание -->
<div>
    <div><label class="title-label">Питание</label></div>
    <input type="text" name="edit-food" size="80" value="<?php echo set_value('edit-food', $obj['food']) ?>"/>
</div>

<!-- Пляж -->
<div>
    <div><label class="title-label">Пляж</label></div>
    До пляжа(м): <input type="text" name="edit-beach-distance" size="3" value="<?php echo set_value('edit-beach-distance', $obj['beach_distance']); ?>"/>
    Тип: <select name="edit-beach-type">
        <option value="0"></option>
        <?php foreach ($beachs as $b) : ?>
        <option value="<?php echo $b['id'] ?>" <?php echo set_select('edit-beach-type', $b['id'], $b['id'] == $obj['beach_id'] ? TRUE : FALSE); ?> ><?php echo $b['name'] ?></option>
        <?php endforeach; ?>
    </select>
</div>

<!-- В номерах -->
<div id="edit-room-wrap">
    <div><label class="title-label">В номерах</label></div>
    <?php foreach ($room as $rm) : ?>
        <div>
            <label>
                <input type="checkbox" name="edit-room[]" value="<?php echo $rm['url_name'];?>"
                    <?php echo set_checkbox('edit-room[]', $rm['url_name'], in_array($rm['url_name'], $obj['room']) ? TRUE : FALSE); ?>
                />
                <?php echo $rm['name']; ?>
            </label>
        </div>
    <?php endforeach; ?>
</div>

<!-- Инфраструктура -->
<div id="edit-infrastructure-wrap">
    <div><label class="title-label">Инфраструктура</label></div>
    <?php foreach ($infrastructure as $str) : ?>
        <div>
            <label>
                <input type="checkbox" name="edit-infrastructure[]" value="<?php echo $str['url_name'];?>"
                    <?php echo set_checkbox('edit-infrastructure[]', $str['url_name'], in_array($str['url_name'], $obj['infrastructure']) ? TRUE : FALSE); ?>
                />
                <?php echo $str['name']; ?>
            </label>
        </div>
    <?php endforeach; ?>
</div>

<!-- Сервис -->
<div id="edit-service-wrap">
    <div><label class="title-label">Сервис</label></div>
    <?php foreach ($service as $ser) : ?>
        <div>
            <label>
                <input type="checkbox" name="edit-service[]" value="<?php echo $ser['url_name'];?>"
                    <?php echo set_checkbox('edit-service[]', $ser['url_name'], in_array($ser['url_name'], $obj['service']) ? TRUE : FALSE); ?>
                />
                <?php echo $ser['name']; ?>
            </label>
        </div>
    <?php endforeach; ?>
</div>

<!-- Развлечения и спорт -->
<div id="edit-entertainment-wrap">
    <div><label class="title-label">Развлечения и спорт</label></div>
    <?php foreach ($entertainment as $e) : ?>
        <div>
            <label>
                <input type="checkbox" name="edit-entertainment[]" value="<?php echo $e['url_name'];?>"
                    <?php echo set_checkbox('edit-entertainment[]', $e['url_name'], in_array($e['url_name'], $obj['entertainment']) ? TRUE : FALSE); ?>
                />
                <?php echo $e['name']; ?>
            </label>
        </div>
    <?php endforeach; ?>
</div>

<!-- Для детей -->
<div>
    <div><label class="title-label">Для детей</label></div>
    <?php foreach ($for_children as $fc) : ?>
        <div>
            <label>
                <input type="checkbox" name="edit-for-children[]" value="<?php echo $fc['url_name'];?>"
                    <?php echo set_checkbox('edit-for-children[]', $fc['url_name'], in_array($fc['url_name'], $obj['for_children']) ? TRUE : FALSE); ?>
                />
                <?php echo $fc['name']; ?>
            </label>
        </div>
    <?php endforeach; ?>
</div>

<!-- Полное описание -->
<div>
    <div><label class="title-label">Полное описание</label></div>
    <?php $this->ckeditor->editor('edit-body', html_entity_decode(set_value('edit-body', $obj['body']))); ?>
</div>

<!-- Настройки публикации -->
<div>
    <div><label class="title-label">Настройки публикации</label></div>
    <label>
        <input type="checkbox" name="edit-published" value="1" <?php echo set_checkbox('edit-published', '1', $obj['published'] ? TRUE : FALSE); ?> />
        Опубликовано
    </label>
    <label>
        <select name="edit-priority">
            <option value="0">0</option>
            <?php for ($i = 1; $i < 10; $i++): ?>
                <option value="<?php echo $i*10; ?>" <?php echo set_select('edit-priority', $i*10, $i*10 == $obj['priority'] ? TRUE : FALSE); ?>><?php echo $i*10; ?></option>
            <?php endfor; ?>
        </select> Приоритет
    </label>
</div>

<!-- Номерной фонд -->
<div>
    <div><label class="title-label">Номерной фонд</label></div>
    <input name="add-room" type="button" value="добавить" />
</div>

<input type="hidden" name="module-name" value="event">

<input type="submit" value="Сохранить" />

<?php echo form_close(); ?>