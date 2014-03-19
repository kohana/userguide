<?php defined('SYSPATH') OR die('No direct script access.') ?>

<h1>User Guide</h1>
<p>The following modules have userguide pages:</p>
<?php if( ! empty($modules)): ?>
	<?php foreach($modules as $url => $options): ?>
	<p>
		<strong><?php echo HTML::anchor(Route::url('docs/guide', array('module' => $url)), $options['name']) ?></strong> -
		<?php echo $options['description'] ?>
	</p>
	<?php endforeach ?>
<?php else: ?>
	<p class="error">I couldn't find any modules with userguide pages.</p>
<?php endif ?>
