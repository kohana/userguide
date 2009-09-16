<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<title><?php echo $title ?> | Kodoc</title>

<?php foreach ($styles as $style => $media) echo HTML::style($style, array('media' => $media), TRUE), "\n" ?>

<?php foreach ($scripts as $script) echo HTML::script($script, NULL, TRUE), "\n" ?>

</head>
<body>

<div id="topbar">
	<div class="container">
		<div class="span-17 suffix-1">
			<ul class="breadcrumb">
			<?php foreach ($breadcrumb as $link => $title): ?>
				<li><?php echo is_int($link) ? $title : HTML::anchor($link, $title) ?></li>
			<?php endforeach ?>
			</ul>
		</div>

		<div class="translations span-6 last">
			<?php echo form::open(NULL, array('method' => 'get')) ?>
				<?php echo form::select('lang', $translations, $lang) ?>
			<?php echo form::close() ?>
		</div>
	</div>
</div>

<div id="docs" class="container">
	<div id="content" class="span-17 suffix-1 colborder">
		<?php echo $content ?>
	</div>

	<div id="menu" class="span-6 last">
		<?php echo $menu ?>
	</div>
</div>

<div id="footer" class="container">
	<div class="span-17 suffix-1">
		<p class="copyright">&copy; 2008-2009 Kohana Team</p>
	</div>
	<div class="span-6 last">
		<p class="powered">Powered by <?php echo HTML::anchor('http://kohanaphp.com/', 'Kohana') ?> v<?php echo Kohana::VERSION ?></p>
	</div>
</div>

</body>
</html>
