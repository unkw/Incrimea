<?php echo form_open('filter', array('id' => 'filters-form', 'method' => 'get')); ?>

    <h4>Тип контента</h4>
    <div>
        <label>
            <input type="radio" name="type" value="objects"
            <?php if ('objects' == $params['type']) echo 'checked="checked"'; ?>
                />
            Отели
        </label>
    </div>
    <div>
        <label>
            <input type="radio" name="type" value="events"
            <?php if ('events' == $params['type']) echo 'checked="checked"'; ?>
                />
            События
        </label>
    </div>
    <div>
        <label>
            <input type="radio" name="type" value="articles"
            <?php if ('articles' == $params['type']) echo 'checked="checked"'; ?>
                />
            Статьи
        </label>
    </div>

    <!-- Дополнительные фильтры для отелей -->
    <?php if ($params['type'] == 'objects') : ?>

    <!-- Пляж -->
    <h4>Расстояние до пляжа</h4>
    <div class="filters-box">
        <div><label>Не более </label> <input type="text" name="distance" size="3" />м</div>
    </div>

    <a href="#" class="filters-title">Тип пляжа</a>
    <div>
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

    <h4>Места отдыха</h4>
    <?php foreach ($resorts as $r): ?>
    <div>
        <label>
            <input type="checkbox" name="resorts[]" value="<?php echo $r['url_name']; ?>"
            <?php if (in_array($r['url_name'], $params['resorts'])) echo 'checked="checked"'; ?>
            />
            <?php echo $r['name']; ?>
        </label>
    </div>
    <?php endforeach; ?>

    <input type="submit" value="Показать" />
<?php echo form_close(); ?>