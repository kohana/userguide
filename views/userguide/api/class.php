<h1>
	<?php echo $doc->modifiers, $doc->class->name ?>
	<?php $parent = $doc->class; ?>
	<?php while ($parent = $parent->getParentClass()): ?><br/>
	<small>&raquo; <?php echo HTML::anchor($route->uri(array('class' => $parent->name)), $parent->name) ?></small>
	<?php endwhile ?>
</h1>

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
