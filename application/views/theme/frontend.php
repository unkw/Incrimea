<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $head_title; ?></title>

<?php print $metatags; ?>

<link rel="stylesheet" href="<?php print base_url(); ?>css/ui.css" type="text/css" media="screen, projection" />
<link rel="stylesheet" href="<?php print base_url(); ?>css/style.css" type="text/css" media="screen, projection" />
<link rel="stylesheet" href="<?php print base_url(); ?>css/colorbox.css" type="text/css" media="screen, projection" />
<link rel="stylesheet" href="<?php print base_url(); ?>css/objects.css" type="text/css" media="screen, projection" />

<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.colorbox.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/filters.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/resorts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/objects.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/init.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/debug.js"></script>

</head>

<body class="<?php print $body_classes; ?>">

<div id="wrapper">

    <div id="header">
    </div>

    <div id="middle">

        <div id="container">
        <div id="content">
            <h1><?php print $title; ?></h1>
            <?php print $content; ?>
        </div><!-- #content-->
        </div><!-- #container-->

        <div class="sidebar" id="sideLeft">
            <?php if ($left): ?>
                <?php print $left; ?>
            <?php endif; ?>
            
            <?php Widget::run('user_login'); ?>
            
        </div>

    </div>

    <div id="footer">
        <?php print href('user/login', 'Вход'); ?>
    </div>

</div>

</body>
</html>