# 分析器

Kohana 提供一种非常简单的方法来显示你的程序的统计信息

1. 普通 [Kohana] 方法调用
2. 请求
3. [Database] 查询
4. 程序的平均运行时间

## 实例

你可以在任何时候显示或收集当前 [profiler] 统计：

~~~
<div id="kohana-profiler">
<?php echo View::factory('profiler/stats') ?>
</div>
~~~

## 预览

{{profiler/stats}}