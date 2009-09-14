<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<title><?php echo $title ?> | Kodoc</title>

<?php foreach ($styles as $style => $media) echo html::style($style, array('media' => $media), TRUE), "\n" ?>

</head>
<body>

<ul id="breadcrumb">
<?php foreach ($breadcrumb as $link => $title): ?>
	<li><?php echo is_int($link) ? $title : HTML::anchor($link, $title) ?></li>
<?php endforeach ?>
</ul>

<div id="docs">
	<div id="content">

		<?php echo $content ?>
	</div>

	<div id="toc" class="menu">
		<?php echo $menu ?>
	</div>
</div>

<?php if (Kohana::$profiling) echo View::factory('profiler/stats') ?>

</body>
</html>
