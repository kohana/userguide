<h1>
	<?php echo $doc->modifiers, $doc->class->name ?>
	<?php $parent = $doc->class; ?>
	<?php while ($parent = $parent->getParentClass()): ?><br/>
	<small>&raquo; <?php echo HTML::anchor($route->uri(array('class' => $parent->name)), $parent->name) ?></small>
	<?php endwhile ?>
</h1>

<h2 id="toc">Class Contents</h2>
<div class="toc span-17 last">
	<div class="constants span-5">
		<h3>Constants</h3>
		<ul>
		<?php if ($doc->constants): ?>
		<?php foreach ($doc->constants as $name => $value): ?>
			<li><a href="#constant:<?php echo $name ?>"><?php echo $name ?></a></li>
		<?php endforeach ?>
		<?php else: ?>
			<li><em>None</em></li>
		<?php endif ?>
		</ul>
	</div>
	<div class="properties span-6">
		<h3>Properties</h3>
		<ul>
		<?php if ($properties = $doc->properties()): ?>
		<?php foreach ($properties as $prop): ?>
			<li><a href="#property:<?php echo $prop->property->name ?>">$<?php echo $prop->property->name ?></a></li>
		<?php endforeach ?>
		<?php else: ?>
			<li><em>none</em></li>
		<?php endif ?>
		</ul>
	</div>
	<div class="methods span-6 last">
		<h3>Methods</h3>
		<ul>
		<?php if ($methods = $doc->methods()): ?>
		<?php foreach ($methods as $method): ?>
			<li><a href="#<?php echo $method->method->name ?>"><?php echo $method->method->name ?>()</a></li>
		<?php endforeach ?>
		<?php else: ?>
			<li><em>none</em></li>
		<?php endif ?>
		</ul>
	</div>
</div>

<?php echo $doc->description ?>

<?php if ($doc->tags) echo View::factory('userguide/api/tags')->set('tags', $doc->tags) ?>

<?php if ($doc->constants): ?>
<div class="constants">
<h2 id="constants">Constants</h2>
<dt>
<?php foreach ($doc->constants as $name => $value): ?>
<dt id="constant:<?php echo strtolower($name) ?>"><?php echo $name ?></dt>
<dd><?php echo $value ?></dd>
<?php endforeach ?>
</dt>
</div>
<?php endif ?>

<?php if ($properties = $doc->properties()): ?>
<h2 id="properties">Properties</h2>
<div class="properties">
<dt>
<?php foreach ($properties as $prop): ?>
<dt id="property:<?php echo strtolower($prop->property->name) ?>"><?php echo $prop->modifiers ?> <code><?php echo $prop->type ?></code> <?php echo $prop->property->name ?></dt>
<dd><?php echo $prop->description ?></dd>
<dd><?php echo $prop->value ?></dd>
<?php endforeach ?>
</dt>
</div>
<?php endif ?>

<?php if ($methods = $doc->methods()): ?>
<h2 id="methods">Methods</h2>
<div class="methods">
<?php foreach ($methods as $method): ?>
<?php echo View::factory('userguide/api/method')->set('doc', $method) ?>
<?php endforeach ?>
</div>
<?php endif ?>
