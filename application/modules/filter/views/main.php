<!-- Объекты -->
<div id="objects-column" class="filter-column">
<h2>Отели</h2>
<?php if ($objects): ?>

<?php foreach ($objects as $obj) : ?>

    <div class="content-items">
        <!-- Название -->
        <h3><?php print href($obj['alias'], $obj['title']); ?></h3>
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
</div>

<!-- Статьи -->
<div id="articles-column" class="filter-column">
<h2>Статьи</h2>
<?php if ($articles): ?>
<?php foreach ($articles as $a) : ?>

    <div class="content-items">
        <!-- Название -->
        <h3><?php echo href($a['alias'], $a['title']); ?></h3>
        <!-- Место отдыха -->
        <div><?php echo $a['resort']; ?></div>
        <div>
            <!-- Превью -->
            <img src="<?php echo base_url().'images/article/thumb/'.$a['image_src']; ?>" alt="" align="left"/>
            <!-- Краткое описание -->
            <?php echo $a['preview']; ?>
        </div>
        <div class="clear"></div>
    </div>

<?php endforeach; ?>
<?php if ($pager) echo $pager; ?>
<?php else: ?>
    <div>Ничего не найдено</div>
<?php endif; ?>
</div>

<!-- События -->
<?php if ($events): ?>

    <div id="articles-wrap" class="filter-content">
    <h2>События</h2>
    <?php foreach ($events as $e) : ?>

        <div class="content-items">
            <!-- Название -->
            <h3><?php echo href($e['alias'], $e['title']); ?></h3>
            <!-- Место отдыха -->
            <div><?php echo $e['resort']; ?></div>
            <div>
                <!-- Превью -->
                <img src="<?php echo base_url().'images/event/thumb/'.$e['image_src']; ?>" alt="" align="left"/>
                <!-- Краткое описание -->
                <?php echo $e['preview']; ?>
            </div>
            <div class="clear"></div>
        </div>

    <?php endforeach; ?>

    <?php if ($pager) echo $pager; ?>

    </div>

<?php endif; ?>