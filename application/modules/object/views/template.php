<div class="content">

    <!-- Место отдыха -->
    <div>
        <span class="field-label">Курорт: </span>
        <?php echo $resort; ?>
    </div>

    <!-- Тип объекта -->
    <div>
        <span class="field-label">Тип: </span>
        <?php echo $type; ?>
    </div>

    <!-- Галерея объекта -->
    <div>
        <div class="field-label">Галерея: </div>
        <?php foreach ($images as $img): ?>
        <img src="<?php echo base_url().'images/object/thumb/'.$img; ?>" alt="" />
        <?php endforeach; ?>
    </div>

    <!-- Месторасположение -->
    <div>
        <span class="field-label">Месторасположение: </span>
        <?php echo $location; ?>
    </div>

    <!-- Цена от -->
    <div>
        <span class="field-label">Цена от: </span>
        <?php echo '$'.$price; ?>
    </div>

    <!-- Питание -->
    <div>
        <span class="field-label">Питание: </span>
        <?php echo $food; ?>
    </div>

    <!-- До пляжа -->
    <div>
        <span class="field-label">До пляжа: </span>
        <?php echo $beach_distance.' м'; ?>
    </div>

    <!-- Тип пляжа -->
    <div>
        <span class="field-label">Тип пляжа: </span>
        <?php echo $beach; ?>
    </div>

    <!-- В номерах -->
    <div>
        <div class="field-label">В номерах: </div>
        <ul id="obj-room">
        <?php foreach ($room as $r): ?>
            <li class="<?php echo $r['url_name']; ?>">
                <?php echo $r['name']; ?>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>

    <!-- Инфраструктура -->
    <div>
        <div class="field-label">Инфраструктура: </div>
        <ul id="obj-infrastructure">
        <?php foreach ($infrastructure as $inf): ?>
            <li class="<?php echo $inf['url_name']; ?>">
                <?php echo $inf['name']; ?>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>

    <!-- Сервис -->
    <div>
        <div class="field-label">Сервис: </div>
        <ul id="obj-service">
        <?php foreach ($service as $ser): ?>
            <li class="<?php echo $ser['url_name']; ?>">
                <?php echo $ser['name']; ?>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>

    <!-- Развлечения и спорт -->
    <div>
        <div class="field-label">Развлечения и спорт: </div>
        <ul id="obj-entertainment">
        <?php foreach ($entertainment as $e): ?>
            <li class="<?php echo $e['url_name']; ?>">
                <?php echo $e['name']; ?>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>

    <!-- Для детей -->
    <div>
        <div class="field-label">Для детей: </div>
        <ul id="obj-for-children">
        <?php foreach ($for_children as $fc): ?>
            <li class="<?php echo $fc['url_name']; ?>">
                <?php echo $fc['name']; ?>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>

    <!-- Полное описание -->
    <div>
        <div class="field-label">Полное описание: </div>
        <?php echo $body; ?>
    </div>

    <div class="comments">

        <script type="text/javascript">
          VK.init({apiId: 2800590, onlyWidgets: true});
        </script>
        
        <!-- Put this div tag to the place, where the Comments block will be -->
        <div id="vk_comments"></div>
        <script type="text/javascript">
        VK.Widgets.Comments("vk_comments", {limit: 10, width: "600", attach: "*"});
        </script>
    </div>

</div>