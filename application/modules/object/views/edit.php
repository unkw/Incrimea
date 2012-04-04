<!-- System messages -->
<?php if (validation_errors() || isset($errors)): ?>

<div id="error_msg" class="message-box">
    <?php echo validation_errors(); ?>
    <?php if (isset($errors)) print $errors; ?>
</div>

<?php endif; ?>

<!-- Edit Form -->
<?php echo form_open_multipart('', array('id' => 'edit-content')); ?>

<table><tbody><tr>

<td class="left-col">

    <!-- ID контента  -->
    <input type="hidden" name="edit-id" value="<?php echo $obj['id']; ?>" />

    <!-- Название  -->
    <div class="field-wrapper">
        <div><label class="title-label">Название</label></div>
        <input type="text" name="edit-title" size="50" value="<?php echo set_value('edit-title', $obj['title']) ?>"/>
    </div>
    
    <!-- Информация об отеле  -->
    <div class="field-wrapper">
        <div><label class="title-label">Информация (Для администрации)</label></div>
        <textarea name="edit-info" rows="2" cols="50" style="width: 100%;"><?php echo set_value('edit-info', $obj['info']) ?></textarea>        
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

    <!-- Тип -->
    <div>
        <div><label class="title-label">Тип</label></div>
        <select name="edit-types">
        <?php foreach ($types as $t) : ?>
            <option value="<?php echo $t['url_name'];?>" <?php echo set_select('edit-types', $t['url_name'], $t['url_name'] == $obj['type_id'] ? TRUE : FALSE); ?>>
                <?php echo $t['name'];?>
            </option>
        <?php endforeach; ?>
        </select>
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
        <div class="beach-distance">
            <div class="sub-label">До пляжа(м):</div><input type="text" name="edit-beach-distance" value="<?php echo set_value('edit-beach-distance', $obj['beach_distance']); ?>"/>
        </div>
        <div class="beach-type">
            <div class="sub-label">Тип:</div>
            <select name="edit-beach-type">
                <option value=""></option>
                <?php foreach ($beachs as $b) : ?>
                <option value="<?php echo $b['url_name'] ?>" <?php echo set_select('edit-beach-type', $b['url_name'], $b['url_name'] == $obj['beach_id']); ?> ><?php echo $b['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
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
                    <option value="<?php echo $i; ?>" <?php echo set_select('edit-priority', $i, $i == $obj['priority'] ? TRUE : FALSE); ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select> Приоритет
        </label>
    </div>

    <!-- Алиас -->
    <?php print $alias; ?>

    <input type="hidden" name="module-name" value="event">

    <input type="submit" value="Сохранить" />
    <input type="button" value="Удалить" onclick="document.location = '<?php echo base_url(); ?>' + 'admin/object/delete/' + '<?php echo $obj['id']; ?>'; return false;" />
    <a href="<?php echo base_url().'admin/object' ?>">Отмена</a>

</td>

<td class="right-col">

    <!-- Галерея объекта -->
    <div id="edit-gallery-wrapper" class="field-wrapper">
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

    <div class="field-wrapper">
        
        <!-- В номерах -->
        <div id="edit-room-wrap" class="checkboxes-field">
            <div><label class="title-label">В номерах</label></div>
            <?php foreach ($room as $rm) : ?>
                <div>
                <?php
                    $data = array(
                        'class' => 'edit-room-'.$rm['url_name'],
                        'name' => 'edit-room[]',
                        'value' => $rm['url_name'],
                        'checked' => in_array($rm['url_name'], $obj['room']),
                        'onchange' => 'Room.syncInRoomParent(this);'
                    );
                ?>
                    <label>
                        <?php echo form_checkbox($data); ?>
                        <?php echo $rm['name']; ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Инфраструктура -->
        <div id="edit-infrastructure-wrap" class="checkboxes-field">
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
        <div id="edit-service-wrap" class="checkboxes-field">
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
        </div>

        <!-- Развлечения и спорт -->
        <div id="edit-entertainment-wrap" class="checkboxes-field">
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

    </div>

    <!-- Полное описание -->
    <div class="field-wrapper">
        <div><label class="title-label">Описание</label></div>
        <?php $this->ckeditor->editor('edit-body', html_entity_decode(set_value('edit-body', $obj['body']))); ?>
    </div>

    <!-- Номерной фонд -->
    <div class="field-wrapper">

        <div><label class="title-label">Номерной фонд</label></div>

        <div class="rooms-foundation">
        <?php $i = 0; ?>
        <?php foreach ($obj['room_found'] as $r) : ?>
            <?php $r['counter'] = $i; ?>
            <?php echo $this->load->view('object/edit-room', array('r'=>$r)); ?>
            <?php $i++; ?>
        <?php endforeach; ?>
        </div>

        <input id="add-room" type="button" value="Добавить номер" />
    </div>

</td>

</tr></tbody></table>

<?php echo form_close(); ?>

<script type="text/javascript">

    $(function(){

        // Календарь для тарифов
        Room.setDatepicker($('.tarifs-field .date'));
        
        // Загрузчик изображений
        $('.uploader').each(function(i){
            Room.initUploader($(this), i);
        });

        // Добавить комнату
        $('#add-room').click(function(){

            var btn = $(this);

            btn.prop('disabled', true);

            $.post('/admin/object/load_edit_room_form', function(html){

                var form = $(html);

                // Порядковый номер
                var id = $('.room-field').length;
                
                // Генерируем name
                form.find('.title').attr('name', 'room['+id+'][title]');
                form.find('.num_beds').attr('name', 'room['+id+'][num_beds]');
                form.find('.num_rooms').attr('name', 'room['+id+'][num_rooms]');
                form.find('.in-room-chbx').attr('name', 'room['+id+'][in_room][]')
                    .change(function(){
                        Room.syncInRoomChild(this);
                    });
                form.find('.date').attr('name', 'room['+id+'][tarifs][0][date][]');
                form.find('.price').attr('name', 'room['+id+'][tarifs][0][price]');
                form.find('.room-id').val(id);

                // Календарь для дат
                Room.setDatepicker(form.find('.tarifs-field .date'));
                
                // Инициализация загрузчика изображений
                Room.initUploader(form.find('.uploader'), id);
                
                // Добавляем в DOM
                $('.rooms-foundation').append(form);

                btn.prop('disabled', false);
            });

            return false;
        });
    });

    var Room = {

        setDatepicker: function(jQueryObj){

            jQueryObj.datepicker({dateFormat: 'dd-mm-yy'});
        },
        
        initUploader: function(jQueryObj, id){
            
            var self = this;
            var picsWrap = jQueryObj.parent('.pics-field');
            
            jQueryObj.damnUploader({
                url: '/admin/object/upload',
                fieldName: 'edit-images',
                onSelect: function(file){
                    self.addImage.call(this, file, id);
                },
                onAllComplete: function(){
                    picsWrap.find('.ajaxloader').hide();
                }
            });
            
            picsWrap.find('#upload-pic').click(function(){
                
                picsWrap.find('.ajaxloader').css('display', 'inline-block');
                
                jQueryObj.damnUploader('startUpload');
                return false;
            });
        },

        addPeriod: function(link){

            var table = $(link).parent().find('.tarifs-table');
            
            var id = $(link).parent().find('.room-id').val();
            var tid = table.find('tr').length;
            
            var tr = $('<tr />');
            $('<td />').html('От: <input type="text" class="date" name="room['+id+'][tarifs]['+tid+'][date][]" value="" >')
                .appendTo(tr);
            $('<td />').html('До: <input type="text" class="date" name="room['+id+'][tarifs]['+tid+'][date][]" value="" >')
                .appendTo(tr);
            $('<td />').html('Цена: <input type="text" class="price" name="room['+id+'][tarifs]['+tid+'][price]" value="" >')
                .appendTo(tr);
                
            this.setDatepicker(tr.find('.date'));
            
            table.append(tr);
        },
        
        addImage: function(file, id){
            
            var self = this;
            
            if (file.size > 2000000) {
                alert('Размер файла не более 2MB ('+file.name+')');
                return;
            }

            if (file.name.match(/[А-Яа-я]+/)) {
                alert('Русские символы в названии файла недопустимы');
                return;
            }
            
            var li = $('<li />');
            var img = $('<img src="" alt="" width="120" />').appendTo(li);
            
            var picList = this.parent('.pics-field').find('.pics-wrapper');
            li.appendTo(picList);
            
            var uploadId = this.damnUploader('addItem', {
                file: file,
                onProgress: function(value) {
                },
                onComplete: function(successfully, data, errorCode) {
                    if(successfully) {
                        img.css('opacity', 1);
                        li.append('<input type="hidden" name="room['+id+'][pics][]" value="'+data+'" />');
                    } else {
                        alert('Ошибка при загрузке. Код ошибки: '+errorCode); // errorCode содержит код HTTP-ответа, либо 0 при проблеме с соединением
                    }
                }
            });
            
            $('<a href="#" class="remove">Удалить</a>').click(function(){
                
                li.remove();
                self.damnUploader('cancel', uploadId);
                return false;
            }).appendTo(li);

            var reader = new FileReader();

            reader.onload = (function(aImg) {

                return function(e) {
                    aImg.css('opacity', 0.6);
                    aImg.attr('src', e.target.result);
                    aImg.attr('width', 120);
                };
            })(img);

            reader.readAsDataURL(file);
        },
        
        /** Синхронизация поля "В номере" (основное поле) */
        syncInRoomParent: function(chbx){
            
            var childChbx = $('input.in-room-'+$(chbx).val());
            
            if ( $(chbx).prop('checked') )
                childChbx.prop('checked', true);
            else
                childChbx.prop('checked', false);
        },
        
        /** Синхронизация поля "В номере" при изменении данных "Номерного фонда" */
        syncInRoomChild: function(chbx){
            
            var mainChbx = $('input.edit-room-'+$(chbx).val());
            
            if ($(chbx).prop('checked')) {
                
                mainChbx.prop('checked', true);
            } else {
                
                var unCheck = true;
                $('input.in-room-'+$(chbx).val()).each(function(){
                    if ( $(this).prop('checked') ) {
                        unCheck = false;
                        return false;
                    }
                });
                if (unCheck)
                    mainChbx.prop('checked', false);
            }
        },
        
        remove: function(link){
          
            $(link).parent().fadeOut(1000, function(){
                $(this).remove();
            });  
        },
        
        removeImage: function(link) {
            
            $(link).parent().remove();
        }
    };

</script>