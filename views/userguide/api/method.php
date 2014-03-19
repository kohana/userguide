<?php defined('SYSPATH') OR die('No direct script access.') ?>

<div class="method">

	<?php 
	$declares = $doc->method->getDeclaringClass();  
	$uri = $route->uri(array('class' => $declares->name));
	?>
	<h3 id="<?php echo $doc->method->name ?>">
		<?php echo $doc->modifiers.$doc->method->name ?>( <?php echo $doc->params ? $doc->params_short() : '' ?>)
		<?php ?>
		<small>(defined in <?php echo HTML::anchor($uri, $declares->name, NULL, NULL, TRUE) ?>)</small>
	</h3>

	<div class="description">
		<?php echo $doc->description ?>
	</div>

	<?php if ($doc->params): ?>
	<h4>Parameters</h4>
	<ul>
	<?php foreach ($doc->params as $param): ?>
		<li>
			<code><?php echo ($param->reference ? 'byref ' : '').($param->type ? $param->type : 'unknown') ?></code>
			<strong><?php echo '$'.$param->name ?></strong>
			<small><?php echo $param->default ? ' = '.$param->default : 'required' ?></small>
			<?php echo $param->description ? ' - '.$param->description : '' ?>
		</li>
	<?php endforeach ?>
	</ul>
	<?php endif ?>

	<?php if ($doc->tags) 
		echo View::factory('userguide/api/tags', array('tags' => $doc->tags)) ?>

	<?php if ($doc->return): ?>
	<h4>Return Values</h4>
	<ul class="return">
	<?php foreach ($doc->return as $set): 
		list($type, $text) = $set ?>
		<li>
			<code><?php echo HTML::chars($type) ?></code>
			<?php echo $text ? ' - '.HTML::chars(ucfirst($text)) : '' ?>
		</li>
	<?php endforeach ?>
	</ul>
	<?php endif ?>

	<?php if ($doc->source): ?>
	<div class="method-source">
		<h4>Source Code</h4>
		<pre><code><?php echo HTML::chars($doc->source) ?></code></pre>
	</div>
	<?php endif ?>

</div>
