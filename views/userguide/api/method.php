<div class="method">

<?php $declares = $doc->method->getDeclaringClass(); ?>
<h3 id="<?php echo $doc->method->name ?>">
	<?php echo $doc->modifiers, $doc->method->name ?>( <?php echo $doc->params ? $doc->params_short() : '' ?>)
	<br/><small>&rsaquo; <?php echo html::anchor($route->uri(array('class' => $declares->name)), $declares->name) ?></small>
</h3>

<div class="description">
<?php echo $doc->description ?>
</div>

<?php if ($doc->tags) echo View::factory('userguide/api/tags')->set('tags', $doc->tags) ?>

<?php // param tables disabled, removed the FALSE AND below to activate ?>
<?php if ( FALSE AND $doc->params): ?>
<h5><?php echo __('Parameters'); ?></h5>
<table>
<tr>
	<th><?php echo __('Parameter'); ?></th>
	<th><?php echo __('Type'); ?></th>
	<th><?php echo __('Description'); ?></th>
	<th><?php echo __('Default'); ?></th>
</tr>
<?php foreach ($doc->params as $param): ?>
<tr>
<td><strong><code><?php echo '$'.$param->name ?></code></strong></td>
<td><code><?php echo $param->byref?'byref ':''.$param->type?$param->type:'unknown' ?></code></td>
<td><?php echo ucfirst($param->description) ?></td>
<td><?php echo $param->default ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif ?>

<?php if ($doc->return): ?>
<h5><?php echo __('Return Values'); ?></h5>
<ul class="return">
<?php foreach ($doc->return as $set): list($type, $text) = $set; ?>
<li><code><?php echo HTML::chars($type) ?></code> <?php echo HTML::chars($text) ?></li>
<?php endforeach ?>
</ul>
<?php endif ?>

<?php if ($doc->source): ?>
<div class="method-source">
<h5><?php echo __('Source Code'); ?></h5>
<pre><code><?php echo HTML::chars($doc->source) ?></code></pre>
</div>
<?php endif ?>

</div>
