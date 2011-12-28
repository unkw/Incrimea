<?php echo form_open('filter', array('id' => 'filters-form', 'method' => 'get')); ?>

    <h4>Тип контента</h4>
    <div>
        <label>
            <input type="radio" name="type" value="objects" />
            Отели
        </label>
    </div>
    <div>
        <label>
            <input type="radio" name="type" value="events" />
            События
        </label>
    </div>
    <div>
        <label>
            <input type="radio" name="type" value="articles" />
            Статьи
        </label>
    </div>

    <h4>Места отдыха</h4>
    <?php foreach ($resorts as $r): ?>
    <div>
        <label>
            <input type="checkbox" name="resorts[]" value="<?php echo $r['url_name']; ?>" />
            <?php echo $r['name']; ?>
        </label>
    </div>
    <?php endforeach; ?>

    <input type="submit" value="Применить" />
<?php echo form_close(); ?>