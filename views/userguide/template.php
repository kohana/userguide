<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $l = substr(I18n::$lang, 0, 2) ?>" lang="<?php echo $l ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<title><?php echo $title ?> | Kohana <?php echo __('User Guide'); ?></title>

<?php foreach ($styles as $style => $media) echo HTML::style($style, array('media' => $media), TRUE), "\n" ?>

<?php foreach ($scripts as $script) echo HTML::script($script, NULL, TRUE), "\n" ?>

<!--[if lt IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<![endif]-->

</head>
<body>

	<div id="header">
		<div class="container clearfix">
			<div id="logo">
				<?php echo HTML::image(Route::get('docs/media')->uri(array('file'=>'img/kohana.png'))) ?>
			</div>
			<div id="mode">
				<?php $route = Request::instance()->route; ?>
				<?php echo HTML::anchor(Route::get('docs/guide')->uri(), 'User Guide',Route::get('docs/guide') == $route ? array('class' => 'current'):array()) ?>
				<?php echo HTML::anchor(Route::get('docs/api')->uri(), 'API Browser',Route::get('docs/api') == $route ? array('class' => 'current'):array()) ?>
			</div>
		</div>
	</div>
	<div id="nav">
		<div class="container clearfix">
			<ul>
				<?php foreach ($breadcrumb as $link => $title): ?>
					<li><?php echo is_int($link) ? $title : HTML::anchor($link, $title) ?></li>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
	<div class="container clearfix" id="body">
		<div id="main">
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
		<div id="menu">
			<?php echo $menu ?>
		</div>
	</div>
	<div id="footer" style="overflow:hidden;">
		<p>
			<?php if (isset($copyright)) echo "<span style='float:left'>$copyright</span>"; ?>
			Powered by <?php echo HTML::anchor('http://kohanaframework.org/', 'Kohana') ?> v<?php echo Kohana::VERSION ?>
		</p>
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
