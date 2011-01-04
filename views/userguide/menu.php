<h2><?php echo __('Modules'); ?></h2>

<?php if( ! empty($modules)): ?>

	<ul>
	<?php foreach($modules as $url => $options): ?>
	
		<li><?php echo html::anchor(Route::get('docs/guide')->uri(array('module' => $url)), $options['name']) ?></li>
	
	<?php endforeach; ?>
	</ul>

<?php else: ?>

	<p class="error"><?php echo __('No modules.'); ?></p>

<?php endif; ?>