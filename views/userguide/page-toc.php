<div id="kodoc-page-toc" class="open">
	<p class="kodoc-toc-header">Page Contents&nbsp;<span style="float:right">[<a href="#" id="kodoc-toc-toggle">hide</a>]</span></p>
	<div id="kodoc-page-toc-content">
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