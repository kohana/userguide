<h1><?php echo __('Available Classes') ?></h1>

<div class="class-list">

	<?php foreach ($classes as $class => $methods): $link = $route->uri(array('class' => $class)) ?>
	<div class="class <?php echo Text::alternate('left', 'right') ?>">
		<h2><?php echo HTML::anchor($link, $class) ?></h2>
		<ul class="methods">
		<?php foreach ($methods as $method): ?>
			<li><?php echo HTML::anchor("{$link}#{$method}", "{$class}::{$method}") ?></li>
		<?php endforeach ?>
		</ul>
	</div>
	<?php endforeach ?>

</div>
