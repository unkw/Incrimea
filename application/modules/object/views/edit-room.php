<div class="room-field">
    
    <!-- Название -->
    <div class="sub-field">
        <label class="sub-label">Название: </label>
        <?php 
            $data = array(
                'class' => 'title',
                'name' => isset($r['counter']) ? 'room['.$r['counter'].'][title]' : '',
                'value' => isset($r['title']) ? $r['title'] : '',
            );
            echo form_input($data);
        ?>
    </div>
    <!-- Кол-во спальных мест -->
    <div class="sub-field">
        <label class="sub-label">Кол-во мест:</label>
        <?php
            $data = array(
                'class' => 'num_beds',
                'name' => isset($r['counter']) ? 'room['.$r['counter'].'][num_beds]' : '',
                'value' => isset($r['num_beds']) ? $r['num_beds'] : '',
            );
            echo form_input($data);
        ?>
    </div>
    <!-- Кол-во комнат -->
    <div class="sub-field">
        <label class="sub-label">Кол-во комнат:</label>
        <?php 
            $data = array(
                'class' => 'num_rooms',
                'name' => isset($r['counter']) ? 'room['.$r['counter'].'][num_rooms]' : '',
                'value' => isset($r['num_rooms']) ? $r['num_rooms'] : '',
            );
            echo form_input($data);
        ?>
    </div>

    <div class="clear"></div>

    <!-- В номере -->
    <div class="in-room-field sub-field">
        <div><label class="sub-label">В номере: </label></div>
        <ul>
        <?php foreach ($room as $row): ?>
            <li><label>
                <?php
                    $data = array(
                        'class'=>'in-room-chbx in-room-'.$row['url_name'],
                        'name' => isset($r['counter']) ? 'room['.$r['counter'].'][in_room][]' : '',
                        'value' => $row['url_name'],
                        'onchange' => 'Room.syncInRoomChild(this);',
                        'checked' => isset($r['in_room']) ? in_array($row['url_name'], $r['in_room']) : 0,
                    );
                ?>
                <?php print form_checkbox($data); ?>
                <?php print $row['name'] ?>
            </label></li>
        <?php endforeach; ?>
        </ul>
    </div>

    <!-- Тарифы -->
    <div class="tarifs-field">
        <div><label class="sub-label">Тарифы: </label></div>
        <input type="hidden" class="room-id" value="<?php if (isset($r['counter'])) echo $r['counter']; ?>" />
        <table class="tarifs-table">
            <?php if (isset($r['tarifs'])) : ?>
            <?php $i = 0; ?>
            <?php foreach ($r['tarifs'] as $t): ?>
            <tr>
                <td>От: <input type="text" class="date" name="room[<?php echo $r['counter']; ?>][tarifs][<?php echo $i; ?>][date][]" value="<?php echo $t['date'][0]; ?>" ></td>
                <td>До: <input type="text" class="date" name="room[<?php echo $r['counter']; ?>][tarifs][<?php echo $i; ?>][date][]" value="<?php echo $t['date'][1]; ?>" ></td>
                <td>Цена: <input type="text" class="price" name="room[<?php echo $r['counter']; ?>][tarifs][<?php echo $i; ?>][price]" value="<?php echo $t['price']; ?>" ></td>
            </tr>
            <?php $i++; ?>
            <?php endforeach; ?>
            <?php endif; ?>
        </table>
        <a href="#" class="add-tarif-period" onclick="Room.addPeriod(this); return false;">Добавить период</a>
    </div>

    <div class="clear"></div>
    
    <div class="pics-field">
        <div><label class="sub-label">Фото: </label></div>
        <input type="file" class="uploader" multiple />
        <span class="ajaxloader"></span>
        <input type="button" id="upload-pic" value="Загрузить" />
        <div class="clear"></div>
        <ul class="pics-wrapper">
        <?php if (isset($r['pics'])) : ?>
        <?php foreach ($r['pics'] as $p): ?>
            <li>
                <img src="<?php echo base_url() ?>images/object/thumb/<?php echo $p; ?>" width="120" />
                <input type="hidden" value="<?php echo $p; ?>" name="room[<?php echo $r['counter']; ?>][pics][]" />
                <a href="#" onclick="Room.removeImage(this); return false;" class="remove">Удалить</a>
            </li>
        <?php endforeach; ?>
        <?php endif; ?>
        </ul>
        <div class="clear"></div>
    </div>
    
    <a href="#" onclick="Room.remove(this); return false;" class="remove-room">Удалить комнату</a>
    
</div>