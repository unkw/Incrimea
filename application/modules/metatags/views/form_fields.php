<div id="metatags">
    
    <div>
        <label class="title-label">Метатеги</label>
    </div>

    <div class="fields">
        <div>Заголовок</div>
        <input type="text" name="edit-metatitle" value="<?php echo $title; ?>" size="80" />

        <div>Ключевые слова</div>
        <textarea name="edit-keywords" cols="80" rows="1" style="width: 100%;"><?php echo $keywords; ?></textarea>
        
        <div>Описание</div>
        <textarea name="edit-desc" cols="80" rows="3" style="width: 100%;"><?php echo $desc; ?></textarea>
    </div>

    <input type="hidden" name="edit-metaid" value="<?php echo $id; ?>" />

</div>
