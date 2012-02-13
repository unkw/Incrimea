<div id="metatags" class="field-wrapper">
    
    <div>
        <label class="title-label">Метатеги</label>
    </div>

    <div class="fields">

        <?php if ($path || $view_path_field): ?>
            <div class="sub-label">Путь</div>
            <input type="text" name="edit-path" value="<?php echo $path; ?>" size="80" />
        <?php endif; ?>

        <div class="sub-label">Заголовок</div>
        <input type="text" name="edit-metatitle" value="<?php echo $title; ?>" size="80" />

        <div class="sub-label">Ключевые слова</div>
        <textarea name="edit-keywords" cols="80" rows="1" ><?php echo $keywords; ?></textarea>
        
        <div class="sub-label">Описание</div>
        <textarea name="edit-desc" cols="80" rows="3" ><?php echo $desc; ?></textarea>
    </div>

    <input type="hidden" name="edit-metaid" value="<?php echo $id; ?>" />

</div>
