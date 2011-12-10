
<?php if ($uid == 1): ?>

<div>Главного администратора удалить нельзя :)</div>

<?php else: ?>

<div>Вы действительно хотите удалить пользователя?</div>

<?php print form_open('', array('id' => 'user-delete-form')); ?>

<input type="hidden" value="<?php echo $uid; ?>" name="uid" />

<input type="submit" value="Удалить">
<a href="<?php echo base_url() . 'admin/user/list'; ?>">Отмена</a>

<?php print form_close(); ?>

<?php endif; ?>