# 约定

鼓励大家遵循 Kohana 的编码样式，Kohana 基于 [BSD/Allman style](http://en.wikipedia.org/wiki/Indent_style#BSD.2FAllman_style) 的编码样式（这里还有一些更多[关于 Kohana 编码样式的描述](http://dev.kohanaframework.org/wiki/kohana2/CodingStyle)）

## 类名和文件位置 {#classes}

在 Kohana 系统中类名严格遵循命名约定才能够[自动加载](using.autoloading)。类名的首字母必须大写，且使用下划线连接单词，千万要注意下划线的重要性，因为它直接关系到文件在文件系统中所存放的位置。

请遵循以下约定：

1. 类名不允许使用骆驼命名法，除非需要创建新一级的目录文件。
2. 所有的类文件的文件名和目录名都必须是小写。
3. 所有的类文件都应该存放在 `classes` 目录下面，它可以是在[级联文件系统](about.filesystem)的任何一级。

[!!] 不像 Kohana v2.x，这里不再区分 "controllers"，"models"，"libraries" 和 "helpers" 文件夹。所有的类都存放在 "classes/" 目录，既可以是完全静态的 辅助函数("helpers")或对象形式的类库("libraries")。你可以使用任意形式的设计模式的类库：静态，单例，适配器等。

## 实例

请大家记着一点在类文件中，类名到下划线意味着是一个新的目录，参考下面例子:

类名                  | 文件路径
----------------------|-------------------------------
Controller_Template   | classes/controller/template.php
Model_User            | classes/model/user.php
Database              | classes/database.php
Database_Query        | classes/database/query.php
Form                  | classes/form.php
