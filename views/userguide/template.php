<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<title><?php echo $title ?> | Kodoc</title>

<?php foreach ($styles as $style => $media) echo HTML::style($style, array('media' => $media), TRUE), "\n" ?>

<?php foreach ($scripts as $script) echo HTML::script($script, NULL, TRUE), "\n" ?>

</head>
<body>

<div id="docs">
	<div id="content">

		<?php echo $content ?>
	</div>

	<div id="toc" class="menu">
		<?php echo $menu ?>
	</div>
</div>

<div id="topbar">
	<ul class="breadcrumb">
	<?php foreach ($breadcrumb as $link => $title): ?>
		<li><?php echo is_int($link) ? $title : HTML::anchor($link, $title) ?></li>
	<?php endforeach ?>
	</ul>

	<?php echo form::open(NULL, array('method' => 'get')) ?>
		<?php echo form::select('lang', $translations, $lang) ?>
	<?php echo form::close() ?>
</div>

</body>
</html>
