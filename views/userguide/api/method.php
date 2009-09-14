<div class="method">
<h3 id="<?php echo $doc->method->name ?>"><?php echo $doc->class->name, '::', $doc->method->name ?></h3>

<div class="description">
<?php echo $doc->description ?>
</div>

<h6>Returns:</h6>
<ul class="return">
<?php foreach ($doc->return as $set): list($type, $text) = $set; ?>
<li><code><?php echo $type ?></code> <?php echo $text ?></li>
<?php endforeach ?>
</ul>

<?php if ($doc->tags) echo View::factory('userguide/api/tags')->set('tags', $doc->tags) ?>
</div>