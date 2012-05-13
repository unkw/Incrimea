<h2>Статьи</h2>
<?php if ($articles): ?>
<?php foreach ($articles as $a) : ?>

    <div class="content-item">
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