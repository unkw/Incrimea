
<?php if (!$this->auth->loggin_in()): ?>

<?php echo form_open('user/login'); ?>

<div>
    <?php echo form_label('Логин/email', 'login'); ?>
    <?php echo form_input(array('name'=>'login')); ?>
</div>
<div>
    <?php echo form_label('Пароль', 'password'); ?>
    <?php echo form_password(array('name'=>'password')); ?>
</div>

    <?php echo form_submit(array('value'=>'Войти')); ?>

<?php echo form_close(); ?>

<?php else : ?>

<div>
    <div>Вы вошли как, <b><?php echo $this->session->userdata('name'); ?></b></div>
    <div><?php echo anchor('admin', 'Админ-панель'); ?></div>
    <?php echo anchor('user/logout', 'Выйти'); ?>
</div>

<?php endif; ?>