<div class="content">

<!-- Объекты -->
<?php if ($objects): ?>

    <div id="objects-wrap" class="filter-content">
    <h2>Отели</h2>
    <?php foreach ($objects as $obj) : ?>

        <div class="content-items">
            <!-- Название -->
            <h3><a href="<?php echo base_url().'object/view/'.$obj['id']; ?>" title=""><?php echo $obj['title']; ?></a></h3>
            <!-- Место отдыха -->
            <div><?php echo $obj['resort']; ?></div>
            <div>
                <!-- Превью -->
                <img src="<?php echo base_url().'images/object/thumb/'.$obj['images'][0]; ?>" alt="" align="left"/>
                <!-- Краткое описание -->
                <?php echo $obj['body']; ?>
            </div>
            <div class="clear"></div>
            <div>
                <!-- Цены от -->
                <?php echo 'Цена от: $' . $obj['min_price']; ?>
                <span> | </span>
                <!-- Инфраструктура -->
                <?php foreach ($obj['structure'] as $s): ?>
                    <?php echo $s['name'].' - '; ?>
                <?php endforeach; ?>
            </div>
        </div>

    <?php endforeach; ?>

    <?php if ($pager) echo $pager; ?>

    </div>

<?php endif; ?>

</div>