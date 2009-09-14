<dl class="tags">
<?php foreach ($tags as $name => $set): ?>
<dt><?php echo $name ?></dt>
<?php foreach ($set as $tag): ?>
<dd><?php echo $tag ?></dd>
<?php endforeach ?>
<?php endforeach ?>
</dl>