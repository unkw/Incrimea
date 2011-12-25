<div class="content">

    <!-- Место отдыха -->
    <div>
        <span class="field-label">Курорт: </span>
        <?php echo $object['resort']; ?>
    </div>

    <!-- Тип объекта -->
    <div>
        <span class="field-label">Тип: </span>
        <?php echo $object['type']; ?>
    </div>

    <!-- Галерея объекта -->
    <div>
        <div class="field-label">Галерея: </div>
        <img src="<?php echo base_url().'images/object/medium/'.$object['images'][0] ?>" alt="" /><br />
        <?php foreach ($object['images'] as $img): ?>
        <img src="<?php echo base_url().'images/object/thumb/'.$img; ?>" alt="" />
        <?php endforeach; ?>
    </div>

    <!-- Цена от -->
    <div>
        <span class="field-label">Цена от: </span>
        <?php echo '$'.$object['min_price']; ?>
    </div>

    <!-- Питание -->
    <div>
        <span class="field-label">Питание: </span>
        <?php echo $object['food']; ?>
    </div>

    <!-- Пляж -->
    <div>
        <span class="field-label">До пляжа: </span>
        <?php echo $object['beach']; ?>
    </div>

    <!-- Номерной фонд -->
    <div>
        <span class="field-label">Номерной фонд: </span>
        <?php echo $object['number_fund']; ?>
    </div>

    <!-- Инфраструктура -->
    <div>
        <div class="field-label">Инфраструктура: </div>
        <ul id="obj-structure">
        <?php foreach ($object['structure'] as $str): ?>
            <li class="<?php echo $str['url_name']; ?>">
                <?php echo $str['name']; ?>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>

    <!-- Полное описание -->
    <div>
        <div class="field-label">Полное описание: </div>
        <?php echo $object['body']; ?>
    </div>

</div>