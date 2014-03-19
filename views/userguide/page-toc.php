<?php defined('SYSPATH') OR die('No direct script access.') ?>

<?php if (is_array($array) and count($array) > 1): ?>
<div class="page-toc">
	<?php foreach ($array as $item): ?>
		<?php if ($item['level'] > 1): ?>
		<?php echo str_repeat('&nbsp;', ($item['level'] - 1) * 4) ?>
		<?php endif ?>
		<?php echo HTML::anchor('#'.$item['id'], $item['name'], NULL, NULL, TRUE) ?><br />
	<?php endforeach ?>
</div>
<?php endif ?>
