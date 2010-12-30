<?php if (is_array($array)): ?>
<div class="page-toc">
	<p class="top bottom"><strong>Page Contents</strong></p>
	<div class="content">
		<?php foreach ($array as $item): ?>
			<?php if ($item['level'] > 1): ?>
			<?php echo str_repeat('&nbsp;', ($item['level'] - 1) * 4) ?>
			<?php endif ?>
			<?php echo HTML::anchor('#'.$item['id'],$item['name']); ?><br />
		<?php endforeach; ?>
	</div>
</div>
<?php endif ?>
