# Profiling

Kohana provides a very simple way to display statistics about your application:

1. Common [Kohana] method calls
2. Requests
3. [Database] queries
4. Average execution times for your application

## Example

You can display or collect the current [profiler] statistics at any time:

~~~
<div id="kohana-profiler">
<?php echo View::factory('profiler/stats') ?>
</div>
~~~

## Preview

{{profiler/stats}}