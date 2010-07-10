<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $l = substr(I18n::$lang, 0, 2) ?>" lang="<?php echo $l ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<title><?php echo $title ?> | Kohana <?php echo __('User Guide'); ?></title>

<?php foreach ($styles as $style => $media) echo HTML::style($style, array('media' => $media), TRUE), "\n" ?>

<?php foreach ($scripts as $script) echo HTML::script($script, NULL, TRUE), "\n" ?>

</head>
<body class="<?php echo $l ?>">

<div id="topbar" class="clear">
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
				<?php echo form::select('lang', $translations, I18n::$lang) ?>
			<?php echo form::close() ?>
		</div>
	</div>
</div>

<div id="docs" class="container clear">
	<div id="content" class="span-17 suffix-1 colborder">
		<?php echo $content ?>

		<?php if (Kohana::$environment === Kohana::PRODUCTION): ?>
		<div id="disqus_thread" class="clear"></div>
		<script type="text/javascript">
			var disqus_identifier = '<?php echo HTML::chars(Request::instance()->uri) ?>';
			(function() {
				var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
				dsq.src = 'http://kohana.disqus.com/embed.js';
				(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
				})();
		</script>
		<noscript><?php echo __('Please enable JavaScript to view the :anchor_open comments powered by Disqus.:anchor_close', array(':anchor_open' => '<a href="http://disqus.com/?ref_noscript=kohana">', ':anchor_close' => '</a>')); ?></noscript>
		<a href="http://disqus.com" class="dsq-brlink">Documentation comments powered by <span class="logo-disqus">Disqus</span></a>
		<?php endif ?>
	</div>

	<div id="menu" class="span-6 last">
		<?php echo $menu ?>
		<?php if (isset($module_menus) AND ! empty($module_menus)) : ?>
			<h3><?php echo __('Modules'); ?></h3>
			<?php echo implode("\n", $module_menus) ?>
		<?php endif; ?>
	</div>
</div>

<div id="footer" class="clear">
	<div class="container">
		<div class="span-17 suffix-1">
			<p class="copyright right">&copy; 2008–<?php echo date('Y') ?> Kohana Team</p>
		</div>
		<div class="span-6 last">
			<p class="powered center">Powered by <?php echo HTML::anchor('http://kohanaframework.org/', 'Kohana') ?> v<?php echo Kohana::VERSION ?></p>
		</div>
	</div>
</div>

<?php if (Kohana::$environment === Kohana::PRODUCTION): ?>
<script type="text/javascript">
//<![CDATA[
(function() {
	var links = document.getElementsByTagName('a');
	var query = '?';
	for(var i = 0; i < links.length; i++) {
	if(links[i].href.indexOf('#disqus_thread') >= 0) {
		query += 'url' + i + '=' + encodeURIComponent(links[i].href) + '&';
	}
	}
	document.write('<script charset="utf-8" type="text/javascript" src="http://disqus.com/forums/kohana/get_num_replies.js' + query + '"></' + 'script>');
})();
//]]>
</script>
<?php endif ?>
</body>
</html>
