<?php defined('SYSPATH') OR die('No direct script access.') ?>

<h4>Tags</h4>

<ul class="tags">
<?php foreach ($tags as $name => $set): ?>
	<li><?php echo ucfirst($name).($set ? ' - '.implode(', ',$set) : '') ?>
<?php endforeach ?>
</ul>
