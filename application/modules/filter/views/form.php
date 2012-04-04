<?php echo form_open(base_url().'filter', array('id' => 'filters-form', 'method' => 'get')); ?>

    <h4>Тип контента</h4>
    <div>
        <label>
        <?php echo form_radio(array('name'=>'type','value'=>'objects','checked'=>'objects' == $params['type'])); ?> Отели
        </label>
    </div>
    <div>
        <label>
        <?php echo form_radio(array('name'=>'type','value'=>'articles','checked'=>'articles' == $params['type'])); ?> Статьи
        </label>
    </div>
    <div>
        <label>
        <?php echo form_radio(array('name'=>'type','value'=>'events','checked'=>'events' == $params['type'])); ?> События
        </label>
    </div>

    <!-- Дополнительные фильтры для отелей -->
    <?php if ($params['type'] == 'objects') : ?>

    <!-- Цена -->
    <h4>Цена</h4>
    <div class="filters-box">
        <div>
            <label>От <input type="text" name="p-min" size="3" value="<?php echo $params['price_min']; ?>" autocomplete="off" /></label>
            <label>До <input type="text" name="p-max" size="3" value="<?php echo $params['price_max']; ?>" autocomplete="off" /></label>
        </div>

        <div id="slider-price-range"></div>
    </div>

    <!-- Пляж -->
    <h4>Расстояние до пляжа</h4>
    <div class="filters-box">
        <div><label>До <input type="text" name="distance" size="3" autocomplete="off" value="<?php echo $params['distance']; ?>"/>м</label></div>
    </div>

    <a href="#" class="filters-title">Тип пляжа</a>
    <div class="filters-box">
        <?php foreach ($beachs as $bch) : ?>
        <div>
            <label>
            <?php
            $data = array(
                'id' => 'beach-'.$bch['url_name'],
                'name'=>'beachs[]',
                'autocomplete'=>'off',
                'value'=>$bch['url_name'],
                'checked'=>in_array($bch['url_name'], $params['beachs'])
            );
            if ($bch['count'] < 1 && !$data['checked']) $data['disabled'] = true;
            ?>
            <?php echo form_checkbox($data); ?>
            <?php echo form_label($bch['name'], $data['id']); ?>
            </label>
        </div>
        <?php endforeach; ?>
    </div>

    <a href="#" class="filters-title">В номерах</a>
    <div class="filters-box">
        <?php foreach ($room as $rm) : ?>
            <div><label>
                <?php
                    $data = array(
                        'name' => 'room[]',
                        'value' => $rm['url_name'],
                        'checked' => in_array($rm['url_name'], $params['room']),
                    );
                    if ($rm['count'] < 1 && !$data['checked']) $data['disabled'] = true;
                ?>
                <?php echo form_checkbox($data); ?>
                <?php echo $rm['name']; ?>
            </label></div>
        <?php endforeach; ?>
    </div>

    <a href="" class="filters-title">Инфраструктура</a>
    <div class="filters-box">
        <?php foreach ($infrastructure as $inf) : ?>
            <div><label>
                <?php
                    $data = array(
                        'name' => 'infr[]',
                        'value' => $inf['url_name'],
                        'checked' => in_array($inf['url_name'], $params['infr']),
                    );
                    if ($inf['count'] < 1 && !$data['checked']) $data['disabled'] = true;
                ?>
                <?php echo form_checkbox($data); ?>
                <?php echo $inf['name']; ?>
            </label></div>
        <?php endforeach; ?>
    </div>

    <a href="" class="filters-title">Сервис</a>
    <div class="filters-box">
        <?php foreach ($service as $ser) : ?>
            <div><label>
                <?php
                    $data = array(
                        'name' => 'service[]',
                        'value' => $ser['url_name'],
                        'checked' => in_array($ser['url_name'], $params['service']),
                    );
                    if ($ser['count'] < 1 && !$data['checked']) $data['disabled'] = true;
                ?>
                <?php echo form_checkbox($data); ?>                    
                <?php echo $ser['name']; ?>
            </label></div>
        <?php endforeach; ?>
    </div>

    <a href="" class="filters-title">Развлечения и спорт</a>
    <div class="filters-box">
        <?php foreach ($entertainment as $ent) : ?>
            <div><label>
                <?php
                    $data = array(
                        'name' => 'entment[]',
                        'value' => $ent['url_name'],
                        'checked' => in_array($ent['url_name'], $params['entment']),
                    );
                    if ($ent['count'] < 1 && !$data['checked']) $data['disabled'] = true;
                ?>
                <?php echo form_checkbox($data); ?>                     
                <?php echo $ent['name']; ?>
            </label></div>
        <?php endforeach; ?>
    </div>

    <a href="" class="filters-title">Для детей</a>
    <div class="filters-box">
        <?php foreach ($for_children as $ch) : ?>
            <div><label>
                <?php
                    $data = array(
                        'name' => 'child[]',
                        'value' => $ch['url_name'],
                        'checked' => in_array($ch['url_name'], $params['child']),
                    );
                    if ($ch['count'] < 1 && !$data['checked']) $data['disabled'] = true;
                ?>
                <?php echo form_checkbox($data); ?>                    
                <?php echo $ch['name']; ?>
            </label></div>
        <?php endforeach; ?>
    </div>

    <?php endif; ?> <!-- END Дополнительные фильтры для отелей -->

    <!-- Места отдыха -->
    <h4>Выберите местарасположение</h4>
    <div class="filters-box">
        <!-- Выбранные места отдыха -->
        <div class="resorts-selected"></div>
        
        <!-- Тизер карты -->
        <div class="filters-box">
            <a class="small-map" href="#crimea-map" title="Выберите место отдыха"></a>
        </div>
        
    </div>

    <div class="filters-box">
        <input type="submit" value="Показать" />
        <a href="<?php echo base_url(); ?>" title="">Сбросить все фильтры</a>
    </div>
<?php echo form_close(); ?>

<!-- Контейнер карты и мест отдыха -->
<div style="display: none">
    <div id="crimea-map">

        <!-- Карта Крыма -->
        <div class="map"></div>

        <!-- Чекбоксы выбора мест отдыха -->
        <div class="resorts"></div>

    </div>
</div>

<!-- Инициализация карты и элементов управления местами отдыха -->
<script type="text/javascript">
ResortManager.$_GET = <?php echo json_encode($params['resorts']); ?>;
<?php foreach ($resorts as $r): ?>
ResortManager.collection.push(new ResortInstance(<?php echo json_encode($r); ?>));
<?php endforeach; ?>
ResortManager.init();
</script>