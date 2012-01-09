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

    <a href="#" class="filters-title"><h4>В номерах</h4></a>
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

    <?php endif; ?>

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