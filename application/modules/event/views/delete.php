<div>Вы действительно хотите удалить событие?</div>

<?php print form_open('', array('id' => 'event-delete-form')); ?>

<input type="hidden" value="<?php echo $id; ?>" name="id" />

<input type="submit" value="Удалить">
<a href="<?php echo base_url() . 'admin/event'; ?>">Отмена</a>

<?php print form_close(); ?>