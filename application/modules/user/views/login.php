<?php if ($error_login): ?>
    <div class="error"><?php print $error_login; ?></div>
<?php endif; ?>

<?php print form_open('user/login', array('id' => 'user-login')); ?>

<?php echo form_error('user_check'); ?>

<div id="login-wrapper" class="form-item">
    <div><label>Имя или e-mail: </label></div>
    <?php echo form_error('login'); ?>
    <input type="text" name="login" id="login" class="form-text" value="<?php echo set_value('login'); ?>"/>
</div>

<div id="password-wrapper" class="form-item">
    <div><label>Пароль: </label></div>
    <?php echo form_error('password'); ?>
    <input type="password" name="password" id="password" class="form-text"/>
</div>

<input type="submit" value="Войти" id=""/>

<?php print form_close(); ?>

