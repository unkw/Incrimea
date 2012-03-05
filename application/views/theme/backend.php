<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?php print $title . ' | Incrimea.org'; ?></title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="stylesheet" href="<?php print base_url(); ?>css/admin.css" type="text/css" media="screen, projection" />
    <link href='http://fonts.googleapis.com/css?family=Andika&subset=latin,cyrillic' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="<?php print base_url(); ?>css/jquery-ui.css" type="text/css" media="screen, projection" />
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/admin.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/uploaderObject.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.uploader.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/upload.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.autoSubmit.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/debug.js"></script>
</head>
<body>

<div id="container">

    <ul id="nav">
        <li class="current"><a href="<?php print base_url(); ?>admin">Админ. панель</a></li>
        <li><a href="#">Контент</a>
            <ul>
                <li><a href="<?php print base_url(); ?>admin/page">Страницы</a></li>
                <li><a href="<?php print base_url(); ?>admin/article">Статьи</a></li>
                <li><a href="<?php print base_url(); ?>admin/event">События</a></li>
                <li><a href="<?php print base_url(); ?>admin/object">Объекты</a></li>
            </ul>
            </li>
            <li><a href="#">Модули</a>
                <ul>
                    <li><a href="#">Алиасы</a></li>
                    <li><a href="<?php print base_url(); ?>admin/metatags">Метатеги</a></li>
                    <li><a href="#">Фильтры</a></li>
                </ul>
            </li>
            <li><a href="<?php print base_url(); ?>admin/user/list">Пользователи</a>
                <ul>
                    <li><a href="<?php print base_url(); ?>admin/user/list">Список</a></li>
                    <li><a href="<?php print base_url(); ?>admin/user/add">Добавить</a></li>
                    <li><a href="<?php print base_url(); ?>admin/user/settings">Настройки</a></li>
                </ul>
            </li>
            <li><a href="<?php print base_url(); ?>admin/admin/site_settings">Настройка сайта</a></li>
            <li><a href="#">Отчеты</a></li>
    </ul>

    <div id="content">

        <?php if ($breadcrumb) : ?>
            <div id="breadcrumb">
                <?php print $breadcrumb; ?>
            </div>
        <?php endif; ?>

        <?php echo $this->message->display(); ?>

        <h1><?php print $title; ?></h1>

        <?php if ($submenu) print $submenu ?>

        <?php print $content; ?>

    </div>

</div>

</body>
</html>