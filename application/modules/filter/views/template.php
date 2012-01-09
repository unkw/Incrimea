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

    </div>

<?php endif; ?>

<!-- Статьи -->
<?php if ($articles): ?>

    <div id="articles-wrap" class="filter-content">
    <h2>Статьи</h2>
    <?php foreach ($articles as $a) : ?>

        <div class="content-items">
            <!-- Название -->
            <h3><a href="<?php echo base_url().'article/view/'.$a['id']; ?>" title=""><?php echo $a['title']; ?></a></h3>
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

    </div>

<?php endif; ?>

<!-- События -->
<?php if ($events): ?>

    <div id="articles-wrap" class="filter-content">
    <h2>События</h2>
    <?php foreach ($events as $e) : ?>

        <div class="content-items">
            <!-- Название -->
            <h3><a href="<?php echo base_url().'event/view/'.$e['id']; ?>" title=""><?php echo $e['title']; ?></a></h3>
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

</div>