<div class="page-toc open">
	<p class="header"><strong>Page Contents</strong></p>
	<div class="content">
	<?php if (is_array($array)) : ?>
		<?php foreach ($array as $item): ?>
		
			<?php for ($i = 1; $i < $item['level']; $i++) echo "&nbsp;&nbsp;&nbsp;" ?>
		
			<?php echo HTML::anchor('#'.$item['id'],$item['name']); ?><br />
		
		<?php endforeach; ?>
	<?php else: ?>
		<span>nothing</span>
	<?php endif; ?>
	</div>
</div>