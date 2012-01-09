<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?php print $title; ?></title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="stylesheet" href="<?php print base_url(); ?>css/style.css" type="text/css" media="screen, projection" />
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.formUrl.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/scripts.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/debug.js"></script>
</head>

<body class="<?php print $body_classes; ?>">

<div id="wrapper">

	<div id="header">
	</div><!-- #header-->

	<div id="middle">

		<div id="container">
			<div id="content">
                            <h1><?php print $title; ?></h1>
                            <?php print $content; ?>
			</div><!-- #content-->
		</div><!-- #container-->

		<div class="sidebar" id="sideLeft">
                    <?php if ($filters): ?>
                        <?php print $filters; ?>
                    <?php endif; ?>
		</div><!-- .sidebar#sideLeft -->

		<div class="sidebar" id="sideRight">
			<strong>Right Sidebar:</strong> Integer velit. Vestibulum nisi nunc, accumsan ut, vehicula sit amet, porta a, mi. Nam nisl tellus, placerat eget, posuere eget, egestas eget, dui. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In elementum urna a eros. Integer iaculis. Maecenas vel elit.
		</div><!-- .sidebar#sideRight -->

	</div><!-- #middle-->

	<div id="footer">
            <a href="user/login">Вход</a>
	</div><!-- #footer -->

</div><!-- #wrapper -->

</body>
</html>