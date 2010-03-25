<div class="method">
	
<h3 id="<?php echo $doc->method->name ?>">
	<?php
	echo $doc->modifiers, $doc->method->name,
	' ( ',
	( $doc->params ) ? $doc->params_short() : '' ,
	' ) ', 
	'<br/><small>declared by '.html::anchor($route->uri(array('class'=>$doc->method->getDeclaringClass()->getName())),$doc->method->getDeclaringClass()->getName()).'</small>' ?>
</h3>

<div class="description">
<?php echo $doc->description ?>
</div>

<?php // param tables disabled, removed the FALSE AND below to activate ?>
<?php if ( FALSE AND $doc->params): ?>
<?php //if (false): ?>
<h5>Parameters</h5>
<table>
<tr>
	<th>Parameter</th>
	<th>Type</th>
	<th>Description</th>
	<th>Default</th>
</tr>
<?php foreach ($doc->params as $param): ?>
<tr>
<td><strong><code><?php echo '$'.$param->name ?></code></strong></td>
<td><code><?php echo $param->byref?'byref ':''.$param->type?$param->type:'unknown' ?></code></td>
<td><?php echo ucfirst($param->description) ?></td>
<td><?php echo $param->default ?></td>
</tr>
<?php endforeach ?>
</table>
<?php endif ?>

<?php if ($doc->return): ?>
<h5>Returns:</h5>
<ul class="return">
<?php foreach ($doc->return as $set): list($type, $text) = $set; ?>
<li><code><?php echo HTML::chars($type) ?></code> <?php echo HTML::chars($text) ?></li>
<?php endforeach ?>
</ul>
<?php endif ?>

<?php if ($doc->source): ?>
<div class="method-source">
<h6>Source Code:</h6>
<pre><code><?php echo HTML::chars($doc->source) ?></code></pre>
</div>
<?php endif ?>

<?php if ($doc->tags) echo View::factory('userguide/api/tags')->set('tags', $doc->tags) ?>
</div>
