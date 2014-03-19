<!DOCTYPE html>
<!--[if lt IE 7]><html class="lt-ie9 lt-ie8 lt-ie7" lang="en"><![endif]-->
<!--[if IE 7]><html class="lt-ie9 lt-ie8" lang="en"><![endif]-->
<!--[if IE 8]><html class="lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--><html lang="en"><!--<![endif]-->
<head><?php defined('SYSPATH') OR die('No direct script access.') ?>
	<meta charset="<?php echo Kohana::$charset ?>">
	<title><?php echo $title ?> | Kohana User Guide</title>

<?php foreach ($styles as $style => $media) 
	echo HTML::style($style, array('media' => $media)).PHP_EOL ?>

<?php foreach ($scripts as $script) 
	echo HTML::script($script).PHP_EOL ?>

	<!--[if lt IE 9]>
	<script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>

	<div id="kodoc-header">
		<div class="container">
			<a href="http://kohanaframework.org" id="kodoc-logo">
				<?php echo HTML::image(Route::url('docs/media', array('file' => 'img/kohana.png'))) ?>
			</a>
			<div id="kodoc-menu">
				<ul>
					<li class="guide first">
						<?php echo HTML::anchor(Route::url('docs/guide'), 'User Guide') ?>
					</li>
					<?php if (Kohana::$config->load('userguide.api_browser')): ?>
					<li class="api">
						<?php echo HTML::anchor(Route::url('docs/api'), 'API Browser') ?>
					</li>
					<?php endif ?>
				</ul>
			</div>
			<div id="google_translate_element"></div>
			<script type="text/javascript">
			function googleTranslateElementInit() {
				new google.translate.TranslateElement({
						pageLanguage: 'en', 
						layout: google.translate.TranslateElement.InlineLayout.SIMPLE
					}, 
					'google_translate_element'
				);
			}
			</script>
			<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
		</div>
	</div>

	<div id="kodoc-content">
		<div class="wrapper">
			<div class="container">
				<div class="span-22 prefix-1 suffix-1">
					<ul id="kodoc-breadcrumb">
						<?php foreach ($breadcrumb as $link => $title): ?>
							<?php if (is_string($link)): ?>
							<li><?php echo HTML::anchor($link, $title) ?></li>
							<?php else: ?>
							<li class="last"><?php echo $title ?></li>
							<?php endif ?>
						<?php endforeach ?>
					</ul>
				</div>
				<div class="span-5 prefix-1">
					<div id="kodoc-topics">
						<?php echo $menu ?>
					</div>
				</div>
				<div id="kodoc-body" class="span-17 suffix-1 last">
					<?php echo $content ?>

					<?php if ($show_comments): ?>
					<div id="disqus_thread" class="clear"></div>
					<script type="text/javascript">
					var disqus_identifier = '<?php echo HTML::chars(Request::current()->uri()) ?>';
					(function() {
						var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
						dsq.src = 'http://kohana.disqus.com/embed.js';
						(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
						})();
					</script>
					<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript=kohana">comments powered by Disqus.</a></noscript>
					<a href="http://disqus.com" class="dsq-brlink">Documentation comments powered by <span class="logo-disqus">Disqus</span></a>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>

	<div id="kodoc-footer">
		<div class="container">
			<div class="span-12">
				<?php echo isset($copyright) ? '<p>'.$copyright.'</p>' : '&nbsp;' ?>
			</div>
			<div class="span-12 last right">
				<p>Powered by <?php echo HTML::anchor('http://kohanaframework.org', Kohana::version()) ?></p>
			</div>
		</div>
	</div>

	<?php if ($show_comments): ?>
	<script type="text/javascript">
	(function() {
		var links = document.getElementsByTagName('a');
		var query = 'http://disqus.com/forums/kohana/get_num_replies.js?';
		for(var i = 0; i < links.length; i++) {
			if(links[i].href.indexOf('#disqus_thread') >= 0) {
				query += 'url' + i + '=' + encodeURIComponent(links[i].href) + '&';
			}
		}
		document.write('<script charset="utf-8" type="text/javascript" src="' + query + '"></script>');
	})();
	</script>
	<?php endif ?>

</body>
</html>
