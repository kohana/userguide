# 级联文件系统

Kohana 文件系统是一个单一的目录结构，即根据所有目录（我们称之为包含路径）依次走下去：

1. application
2. modules, in order added
3. system
 	
目录中的文件是按照上面的 1，2，3 的顺序建立的从高到低的优先级，这就有可能使得具有"高等级"目录的同名文件的会重载任何可以低于它的文件内容。

![级联文件系统示意图](img/cascading_filesystem.png)

如果你有一个名为 layout.php 的视图文件分别在 application/views 目录和 system/views 目录，当 layout.php 被执行时 application 目录下的文件执行会高于其他一切的路径。如果你删除了 application/views 的文件，则输出 system/views 下面的文件。 