<!-- Объекты -->
<div id="objects-column" class="filter-column">
<h2>Отели</h2>
<?php if ($objects): ?>

<?php foreach ($objects as $obj) : ?>
    
    <div class="obj-item">
        
        <div class="title-wrap">
            <?php print href($obj['alias'], $obj['title'], array('class'=>'title')); ?>
            <span class="resort"><?php echo $obj['resort']; ?></span>            
        </div>
        
        <div class="img-wrap">
            <?php if (isset($obj['images'][0])) : ?>
            <?php echo href(
                $obj['alias'], 
                '<img class="current" src="'.base_url().'images/object/thumb/'.$obj['images'][0].'" alt="" />',
                array('class'=>'link')
            ); ?>
            <?php endif; ?>                    
            <ul class="small-thumbs">
            <?php for ($i=0; $i < 4; $i++): ?>
                <?php if (isset($obj['images'][$i])) : ?>
                <li><img src="<?php echo base_url();?>images/object/thumb/<?php echo $obj['images'][$i];?>" alt="" /></li>
                <?php endif; ?>
            <?php endfor; ?>
            </ul>            
        </div>
        
        <div class="content-wrap">
            
            <div class="icons-wrap">
                <?php foreach ($obj['infrastructure'] as $s): ?>
                    <?php echo $s['name'].' - '; ?>
                <?php endforeach; ?>                
            </div>
            
            <div class="middle-wrap">
                <div class="info col">Краткая информация</div>
                <div class="desc col"><?php echo $obj['body']; ?></div>
                <div class="price col"><?php echo 'Цена от: $' . $obj['price']; ?></div>
            </div>
            
            <div class="bottom-wrap">
                Подробнее
            </div>
            
        </div>
        
    </div>

<!--    <table class="obj-table-item"><tbody>
        <tr>
            <td class="title-wrap" colspan="3">
                <?php print href($obj['alias'], $obj['title'], array('class'=>'title')); ?>
                <span class="resort"><?php echo $obj['resort']; ?></span>                    
            </td>
        </tr>
        <tr>
            <td class="img-wrap" rowspan="3">
                <?php if (isset($obj['images'][0])) : ?>
                <?php echo href(
                    $obj['alias'], 
                    '<img class="current" src="'.base_url().'images/object/thumb/'.$obj['images'][0].'" alt="" />'
                ); ?>
                <?php endif; ?>                    
                <ul class="small-thumbs">
                <?php for ($i=0; $i < 4; $i++): ?>
                    <?php if (isset($obj['images'][$i])) : ?>
                    <li><img src="<?php echo base_url();?>images/object/thumb/<?php echo $obj['images'][$i];?>" alt="" /></li>
                    <?php endif; ?>
                <?php endfor; ?>
                </ul>
            </td>
            <td colspan="2">
                
                <table class="info-table">
                    <tr>
                        <td colspan="2">
                        <?php foreach ($obj['infrastructure'] as $s): ?>
                            <?php echo $s['name'].' - '; ?>
                        <?php endforeach; ?>                         
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo 'Цена от: $' . $obj['price']; ?></td>
                        <td><?php echo $obj['body']; ?></td>
                    </tr>
                    <tr><td colspan="2">Подробнее</td></tr>
                </table>
                   
            </td>
        </tr>
    </tbody></table>    -->
        
<?php endforeach; ?>


<?php if ($pager) echo $pager; ?>
<?php else: ?>
    <div>Ничего не найдено</div>
<?php endif; ?>
</div>

<!-- Статьи -->
<div id="articles-column" class="filter-column clear">
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
</div>

<!-- События -->
<?php if ($events): ?>

    <div id="articles-wrap" class="filter-content">
    <h2>События</h2>
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

    </div>

<?php endif; ?>