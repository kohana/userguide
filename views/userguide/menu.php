<?php defined('SYSPATH') OR die('No direct script access.') ?>

<h2>Modules</h2>
<?php if( ! empty($modules)): ?>
	<ul>
	<?php foreach($modules as $url => $options): ?>
		<li><?php echo HTML::anchor(Route::url('docs/guide', array('module' => $url)), $options['name']) ?></li>
	<?php endforeach ?>
	</ul>
<?php else: ?>
	<p class="error">No modules.</p>
<?php endif ?>
