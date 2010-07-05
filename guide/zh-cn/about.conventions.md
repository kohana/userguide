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

## 编码标准 {#coding_standards}

In order to produce highly consistent source code, we ask that everyone follow the coding standards as closely as possible.

### Brackets
Please use [BSD/Allman Style](http://en.wikipedia.org/wiki/Indent_style#BSD.2FAllman_style) bracketing.

### 命名约定

Kohana 使用下划线连接命名，而不是驼峰命名。

#### 类

	// 库，使用 _Core 作后缀
	class Beer_Core {

	// 库的继承不需要使用后缀
	class Beer extends Beer_Core

	// 控制器类，使用 _Controller 作后缀
	class Apple_Controller extends Controller {

	// 模型类，使用 _Model 作后缀
	class Cheese_Model extends Model {

	// 辅助类
	class peanut {

当你实例化一个不需要附带参数的类时不需要使用圆括号:

	// 正确:
	$db = new Database;

	// 错误:
	$db = new Database();

#### 函数和方法

函数尽量全小写，并使用下划线分割单词:

	function drink_beverage($beverage)
	{

#### 变量

所有变量尽量全小写，并使用下划线分割单词而不是驼峰:

	// 正确:
	$foo = 'bar';
	$long_example = 'uses underscores';

	// 错误:
	$weDontWantThis = 'understood?';

### 缩进

代码在逻辑上缩进使用制表符(TAB)代替空格。

垂直间距(即多行)使用空格。制表符并不适用于垂直间距主要是因为不同的人可能设置类不同的制表符宽度。

	$text = 'this is a long text block that is wrapped. Normally, we aim for '
		  . 'wrapping at 80 chars. Vertical alignment is very important for '
		  . 'code readability. Remember that all indentation is done with tabs,'
		  . 'but vertical alignment should be completed with spaces, after '
		  . 'indenting with tabs.';

### 字符串连接

不要在连接符左右使用空格:

	// 正确:
	$str = 'one'.$var.'two';

	// 错误:
	$str = 'one'. $var .'two';
	$str = 'one' . $var . 'two';

### 单行表达式

单行 IF 表达式仅用于破坏正常执行的情况(比如，return 或 continue):

	// 可接受:
	if ($foo == $bar)
		return $foo;

	if ($foo == $bar)
		continue;

	if ($foo == $bar)
		break;

	if ($foo == $bar)
		throw new Exception('You screwed up!');

	// 不可接受:
	if ($baz == $bun)
		$baz = $bar + 2;

### 比较操作

使用 OR 和 AND 作为比较符:

	// 正确:
	if (($foo AND $bar) OR ($b AND $c))

	// 错误:
	if (($foo && $bar) || ($b && $c))

if/else Blocks

使用 elseif 而不是 else if:

	// 正确:
	elseif ($bar)

	// 错误:
	else if($bar)

### Switch 结构

每个 case，break 和 default 都应该是独立的一行。每个 case 或 default 里面必须使用一个制表符(TAB)。

	switch ($var)
	{
		case 'bar':
		case 'foo':
			echo 'hello';
		break;
		case 1:
			echo 'one';
		break;
		default:
			echo 'bye';
		break;
	}

### 括号

There should be one space after statement name, followed by a parenthesis. The ! (bang) character must have a space on either side to ensure maximum readability. Except in the case of a bang or type casting, there should be no whitespace after an opening parenthesis or before a closing parenthesis.

	// 正确:
	if ($foo == $bar)
	if ( ! $foo)

	// 错误:
	if($foo == $bar)
	if(!$foo)
	if ((int) $foo)
	if ( $foo == $bar )
	if (! $foo)

### 三元操作

所有的三元操作都应该遵循一种标准格式。表达式左右使用括号，而变量则不需要。

	$foo = ($bar == $foo) ? $foo : $bar;
	$foo = $bar ? $foo : $bar;

所有的比较和操作都必须使用括号括起来作为一个组:

	$foo = ($bar > 5) ? ($bar + $foo) : strlen($bar);

分离复杂的三元操作（三元的第一部分超过了 80 个字符）为多行形式。spaces should be used to line up operators, which should be at the front of the successive lines:

	$foo = ($bar == $foo)
		 ? $foo
		 : $bar;

### 强制类型转换

强制类型转换需要在两边使用空格:

	// 正确:
	$foo = (string) $bar;
	if ( (string) $bar)

	// 错误:
	$foo = (string)$bar;

如果可能，请使用强制类型转换，而不是三元操作:

	// 正确:
	$foo = (bool) $bar;

	// 错误:
	$foo = ($bar == TRUE) ? TRUE : FALSE;

如果强制类型转换整形(int)或布尔型(boolean)，请使用短格式:

	// 正确:
	$foo = (int) $bar;
	$foo = (bool) $bar;

	// 错误:
	$foo = (integer) $bar;
	$foo = (boolean) $bar;

### 常量

常量尽量使用全大写:

	// 正确:
	define('MY_CONSTANT', 'my_value');
	$a = TRUE;
	$b = NULL;

	// 错误:
	define('MyConstant', 'my_value');
	$a = True;
	$b = null;

请把常量放在比较符号的末端:

	// 正确:
	if ($foo !== FALSE)

	// 错误:
	if (FALSE !== $foo)

这是一个略有争议的选择，所以我会解释其理由。如果我们用简单的英语写前面的例子中，正确的例子如下:

	if variable $foo is not exactly FALSE

但是错误的例子可以理解为:

	if FALSE is not exactly variable $foo

由于我们是从左向右读，因此把常量放在第一位根本没有意义。

### 注解

#### 单行注解

单行注解使用 //，或许你在使用下面几种注解方式。请在注解符后面保留一个空格在添加注解。坚决不能使用 #。

	// 正确

	//错误
	// 错误
	# 错误

### 正则表达式

如果编码中使用到正则表达式，请尽量使用 PCRE 风格而不是 POSIX 风格。相比较而言 PCRE 风格更为强大，速度更快。

	// 正确:
	if (preg_match('/abc/i'), $str)

	// 错误:
	if (eregi('abc', $str))

正则表达式使用单引号括起来而不是双引号。单引号的字符串简单而且解析起来更快。 
Unlike double-quoted strings they don't support variable interpolation 
nor integrated backslash sequences like \n or \t, etc.

	// 正确:
	preg_match('/abc/', $str);

	// 错误:
	preg_match("/abc/", $str);

当需要使用正则搜索活替换时，请使用 $n 符号作反向引用，它的效率优于 \\n。

	// 正确:
	preg_replace('/(\d+) dollar/', '$1 euro', $str);

	// 错误:
	preg_replace('/(\d+) dollar/', '\\1 euro', $str);

最后，请注意如果使用 $ 符号匹配字符串末尾是否允许后换行符的话，如果需要可以附加 D 修饰符解决此问题。[更多详情](http://blog.php-security.org/archives/76-Holes-in-most-preg_match-filters.html)。

	$str = "email@example.com\n";

	preg_match('/^.+@.+$/', $str);  // TRUE
	preg_match('/^.+@.+$/D', $str); // FALSE
