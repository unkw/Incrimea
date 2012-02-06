<h2>Отели</h2>
<?php if ($objects): ?>
<?php foreach ($objects as $obj) : ?>

    <div class="content-items">
        <!-- Название -->
        <h3><?php echo href($obj['alias'], $obj['title']); ?></h3>
        <!-- Место отдыха -->
        <div><?php echo $obj['resort']; ?></div>
        <div>
            <!-- Превью -->
            <?php if (isset($obj['images'][0])) : ?>
                <img src="<?php echo base_url().'images/object/thumb/'.$obj['images'][0]; ?>" alt="" align="left"/>
            <?php endif; ?>
            <!-- Краткое описание -->
            <?php echo $obj['body']; ?>
        </div>
        <div class="clear"></div>
        <div>
            <!-- Цены от -->
            <?php echo 'Цена от: $' . $obj['price']; ?>
            <span> | </span>
            <!-- Инфраструктура -->
            <?php foreach ($obj['infrastructure'] as $s): ?>
                <?php echo $s['name'].' - '; ?>
            <?php endforeach; ?>
        </div>
    </div>

<?php endforeach; ?>
<?php if ($pager) echo $pager; ?>
<?php else: ?>
    <div>Ничего не найдено</div>
<?php endif; ?>