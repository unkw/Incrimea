<div>Вы действительно хотите удалить страницу отеля?</div>

<?php print form_open('', array('id' => 'object-delete-form')); ?>

<input type="hidden" value="<?php echo $id; ?>" name="id" />

<input type="submit" value="Удалить">
<a href="<?php echo base_url() . 'admin/object'; ?>">Отмена</a>

<?php print form_close(); ?>