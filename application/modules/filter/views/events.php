<h2>События</h2>
<?php if ($events): ?>
<?php foreach ($events as $e) : ?>

    <div class="content-item">
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

<?php else : ?>
    <div>Ничего не найдено</div>
<?php endif; ?>