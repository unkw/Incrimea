<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?php print $title . ' | Incrimea.org'; ?></title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="stylesheet" href="<?php print base_url(); ?>css/admin.css" type="text/css" media="screen, projection" />
</head>
<body>

    <div id="container">

        <div id="menu">
            <ul class="menu">
                <li><a href="<?php print base_url(); ?>admin">Админ. панель</a></li>
                <li><a href="<?php print base_url(); ?>admin/pages">Контент</a>
                    <ul class="submenu">
                        <li><a href="<?php print base_url(); ?>admin/pages">Список</a></li>
                        <li><a href="<?php print base_url(); ?>admin/pages/add">Создать контент</a></li>
                        <li><a href="<?php print base_url(); ?>admin/pages/categories">Категории</a></li>
                        <li><a href="<?php print base_url(); ?>admin/pages/types">Типы контента</a></li>
                        <li><a href="<?php print base_url(); ?>admin/comments">Комментарии</a></li>
                    </ul>
                </li>
                <li><a href="#">Модули</a>
                    <ul class="submenu">
                        <li><a href="<?php print base_url(); ?>admin/path">Path</a></li>
                        <li><a href="<?php print base_url(); ?>admin/metatags">Metatags</a></li>
                        <li><a href="<?php print base_url(); ?>admin/filters">Filters</a></li>
                    </ul>
                </li>
                <li><a href="<?php print base_url(); ?>admin/user/list">Пользователи</a>
                    <ul class="submenu">
                        <li><a href="<?php print base_url(); ?>admin/user/list">Список</a></li>
                        <li><a href="<?php print base_url(); ?>admin/user/add">Добавить пользователя</a></li>
                        <li><a href="<?php print base_url(); ?>admin/user/settings">Настройки</a></li>
                    </ul>
                </li>
                <li><a href="#">Настройка сайта</a></li>
                <li><a href="#">Отчеты</a></li>
            </ul>
        </div>

        <div id="content">


            <?php if ($breadcrumb) : ?>
                <div id="breadcrumb">
                    <?php print $breadcrumb; ?>
                </div>
            <?php endif; ?>

            <?php echo $this->message->display(); ?>

            <h1><?php print $title; ?></h1>

            <?php print $content; ?>
            
        </div>

    </div>

</body>
</html>