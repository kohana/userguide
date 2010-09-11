<h4>Tags</h4>
<ul class="tags">
<?php foreach ($tags as $name => $set): ?>
<li><?php echo $name ?> - <?php echo implode(', ',$set); ?>
<?php endforeach ?>
</ul>