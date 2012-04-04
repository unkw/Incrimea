<div>Вы действительно хотите удалить статью?</div>

<?php print form_open('', array('id' => 'article-delete-form')); ?>

<input type="hidden" value="<?php echo $id; ?>" name="id" />

<input type="submit" value="Удалить">
<a href="<?php echo base_url() . 'admin/article'; ?>">Отмена</a>

<?php print form_close(); ?>