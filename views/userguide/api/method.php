<div class="method">
<h3 id="<?php echo strtolower($doc->method->name) ?>"><?php echo $doc->modifiers, $doc->method->name, ' ( ', $doc->params, ' )'  ?> <a class="toggle" href="#toc">(top)</a></h3>

<div class="description">
<?php echo $doc->description ?>
</div>

<?php if ($doc->return): ?>
<h6>Returns:</h6>
<ul class="return">
<?php foreach ($doc->return as $set): list($type, $text) = $set; ?>
<li><code><?php echo HTML::chars($type) ?></code> <?php echo HTML::chars($text) ?></li>
<?php endforeach ?>
</ul>
<?php endif ?>

<?php if ($doc->source): ?>
<h6>Source:</h6>
<pre><code><?php echo HTML::chars($doc->source) ?></code></pre>
<?php endif ?>

<?php if ($doc->tags) echo View::factory('userguide/api/tags')->set('tags', $doc->tags) ?>
</div>
