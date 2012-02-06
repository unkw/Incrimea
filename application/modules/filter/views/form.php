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
        <div><label>До </label> <input type="text" name="distance" size="3" value="<?php echo $params['distance']; ?>"/>м</div>
    </div>

    <a href="#" class="filters-title">Тип пляжа</a>
    <div class="filters-box">
        <?php foreach ($beachs as $bch) : ?>
            <div><label>
                <input type="checkbox" name="beachs[]" value="<?php echo $bch['url_name']; ?>"
                <?php if (in_array($bch['url_name'], $params['beachs'])) echo 'checked="checked"'; ?>
                />
                <?php echo $bch['name']; ?>
            </label></div>
        <?php endforeach; ?>
    </div>

    <a href="#" class="filters-title">В номерах</a>
    <div class="filters-box">
        <?php foreach ($room as $rm) : ?>
            <div><label>
                <input type="checkbox" name="room[]" value="<?php echo $rm['url_name']; ?>"
                <?php if (in_array($rm['url_name'], $params['room'])) echo 'checked="checked"'; ?>
                />
                <?php echo $rm['name']; ?>
            </label></div>
        <?php endforeach; ?>
    </div>

    <a href="" class="filters-title">Инфраструктура</a>
    <div class="filters-box">
        <?php foreach ($infrastructure as $inf) : ?>
            <div><label>
                <input type="checkbox" name="infr[]" value="<?php echo $inf['url_name']; ?>"
                <?php if (in_array($inf['url_name'], $params['infr'])) echo 'checked="checked"'; ?>
                />
                <?php echo $inf['name']; ?>
            </label></div>
        <?php endforeach; ?>
    </div>

    <a href="" class="filters-title">Сервис</a>
    <div class="filters-box">
        <?php foreach ($service as $ser) : ?>
            <div><label>
                <input type="checkbox" name="service[]" value="<?php echo $ser['url_name']; ?>"
                <?php if (in_array($ser['url_name'], $params['service'])) echo 'checked="checked"'; ?>
                />
                <?php echo $ser['name']; ?>
            </label></div>
        <?php endforeach; ?>
    </div>

    <a href="" class="filters-title">Развлечения и спорт</a>
    <div class="filters-box">
        <?php foreach ($entment as $ent) : ?>
            <div><label>
                <input type="checkbox" name="entment[]" value="<?php echo $ent['url_name']; ?>"
                <?php if (in_array($ent['url_name'], $params['entment'])) echo 'checked="checked"'; ?>
                />
                <?php echo $ent['name']; ?>
            </label></div>
        <?php endforeach; ?>
    </div>

    <a href="" class="filters-title">Для детей</a>
    <div class="filters-box">
        <?php foreach ($child as $ch) : ?>
            <div><label>
                <input type="checkbox" name="child[]" value="<?php echo $ch['url_name']; ?>"
                <?php if (in_array($ch['url_name'], $params['child'])) echo 'checked="checked"'; ?>
                />
                <?php echo $ch['name']; ?>
            </label></div>
        <?php endforeach; ?>
    </div>

    <?php endif; ?> <!-- END Дополнительные фильтры для отелей -->

    <!-- Места отдыха -->
    <h4>Выберите местарасположение</h4>

    <div class="resorts-selected">

    </div>

    <a class="small-map" href="#crimea-map" title="Выберите место отдыха"></a>

    <div style="display: none">
        <div id="crimea-map">

            <div class="map">

            </div>

            <div class="resorts">
                <ul>
                <?php foreach ($resorts as $r) : ?>
                    <li>
                        <label for="resort-<?php echo $r['url_name']; ?>">
                        <?php
                            $data = array(
                                    'name'    => 'resorts[]',
                                    'id'      => 'resort-' . $r['url_name'],
                                    'value'   => $r['url_name'],
                                    'checked' => in_array($r['url_name'], $params['resorts']),
                                    'autocomplete' => 'off'
                                );
                            echo form_checkbox($data);
                            echo $r['name'];
                        ?>
                        </label>
                    </li>
                <?php endforeach; ?>
                </ul>

                <div class="buttons">
                    <input type="button" value="Добавить" id="resorts-add" />
                    <a href="#" id="resorts-select" >Выбрать все</a>
                    <a href="#" id="resorts-reset" >Отменить выделение</a>
                </div>
            </div>

        </div>
    </div>

    <input type="submit" value="Показать" />

    <a href="<?php echo base_url(); ?>" >Сбросить</a>
<?php echo form_close(); ?>