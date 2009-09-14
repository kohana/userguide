<h1>
	<?php echo $doc->class->name ?>
	<?php $parent = $doc->class; ?>
	<?php while ($parent = $parent->getParentClass()): ?>
	<small>&raquo; <?php echo HTML::anchor($route->uri(array('class' => $parent->name)), $parent->name) ?></small>
	<?php endwhile ?>
</h1>

<?php echo $doc->description ?>

<?php if ($doc->tags) echo View::factory('userguide/api/tags')->set('tags', $doc->tags) ?>

<?php if ($properties = $doc->properties()): ?>
<h2>Properties</h2>
<div class="properties">
<dt>
<?php foreach ($properties as $prop): ?>
<dt><?php echo $prop->modifiers ?> <code><?php echo $prop->type ?></code> <?php echo $prop->property->name ?></dt>
<dd><?php echo $prop->description ?></dd>
<?php endforeach ?>
</dt>
</div>
<?php endif ?>


<?php if ($methods = $doc->methods()): ?>
<h2>Methods</h2>
<?php foreach ($methods as $method): ?>
<?php echo View::factory('userguide/api/method')->set('doc', $method) ?>
<?php endforeach ?>
<?php endif ?>
