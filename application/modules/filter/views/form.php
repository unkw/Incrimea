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

    <input type="submit" value="Применить" />
<?php echo form_close(); ?>