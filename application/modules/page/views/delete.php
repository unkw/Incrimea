<div>Вы действительно хотите удалить страницу?</div>

<?php print form_open('', array('id' => 'page-delete-form')); ?>

<input type="hidden" value="<?php echo $id; ?>" name="id" />

<input type="submit" value="Удалить">
<a href="<?php echo base_url() . 'admin/page'; ?>">Отмена</a>

<?php print form_close(); ?>