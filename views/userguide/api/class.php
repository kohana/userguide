<h1>
	<?php echo $doc->modifiers, $doc->class->name ?>
	<?php $parent = $doc->class; ?>
	<?php while ($parent = $parent->getParentClass()): ?>
	<br/><small>&rsaquo; <?php echo HTML::anchor($route->uri(array('class' => $parent->name)), $parent->name) ?></small>
	<?php endwhile ?>
</h1>

<h2 id="toc"><?php echo __('Class Contents'); ?></h2>
<div class="toc span-17 last">
	<div class="constants span-5">
		<h3><?php echo __('Constants'); ?></h3>
		<ul>
		<?php if ($doc->constants): ?>
		<?php foreach ($doc->constants as $name => $value): ?>
			<li><a href="#constant:<?php echo $name ?>"><?php echo $name ?></a></li>
		<?php endforeach ?>
		<?php else: ?>
			<li><em><?php echo __('None'); ?></em></li>
		<?php endif ?>
		</ul>
	</div>
	<div class="properties span-6">
		<h3><?php echo __('Properties'); ?></h3>
		<ul>
		<?php if ($properties = $doc->properties()): ?>
		<?php foreach ($properties as $prop): ?>
			<li><a href="#property:<?php echo $prop->property->name ?>">$<?php echo $prop->property->name ?></a></li>
		<?php endforeach ?>
		<?php else: ?>
			<li><em><?php echo __('None'); ?></em></li>
		<?php endif ?>
		</ul>
	</div>
	<div class="methods span-6 last">
		<h3><?php echo __('Methods'); ?></h3>
		<ul>
		<?php if ($methods = $doc->methods()): ?>
		<?php foreach ($methods as $method): ?>
			<li><a href="#<?php echo $method->method->name ?>"><?php echo $method->method->name ?>()</a></li>
		<?php endforeach ?>
		<?php else: ?>
			<li><em><?php echo __('one'); ?></em></li>
		<?php endif ?>
		</ul>
	</div>
</div>

<?php echo $doc->description ?>

<?php if ($doc->tags) echo View::factory('userguide/api/tags')->set('tags', $doc->tags) ?>

<p class="note">
<?php if ($path = $doc->class->getFilename()): ?>
Class declared in <tt><?php echo Kohana::debug_path($path) ?></tt> on line <?php echo $doc->class->getStartLine() ?>.
<?php else: ?>
Class is not declared in a file, it is probably an internal <?php echo html::anchor('http://php.net/manual/class.'.strtolower($doc->class->name).'.php', 'PHP class') ?>.
<?php endif ?>
</p>

<?php if ($doc->constants): ?>
<div class="constants">
<h2 id="constants"><?php echo __('Constants'); ?></h2>
<dl>
<?php foreach ($doc->constants as $name => $value): ?>
<dt id="constant:<?php echo $name ?>"><?php echo $name ?></dt>
<dd><?php echo $value ?></dd>
<?php endforeach; ?>
</dl>
</div>
<?php endif ?>

<?php if ($properties = $doc->properties()): ?>
<h2 id="properties"><?php echo __('Properties'); ?></h2>
<div class="properties">
<dl>
<?php foreach ($properties as $prop): ?>
<dt id="property:<?php echo $prop->property->name ?>"><?php echo $prop->modifiers ?> <code><?php echo $prop->type ?></code> $<?php echo $prop->property->name ?></dt>
<dd><?php echo $prop->description ?></dd>
<dd><?php echo $prop->value ?></dd>
<?php endforeach ?>
</dl>
</div>
<?php endif ?>

<?php if ($methods = $doc->methods()): ?>
<h2 id="methods"><?php echo __('Methods'); ?></h2>
<div class="methods">
<?php foreach ($methods as $method): ?>
<?php echo View::factory('userguide/api/method')->set('doc', $method)->set('route', $route) ?>
<?php endforeach ?>
</div>
<?php endif ?>
