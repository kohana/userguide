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

	<?php

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

	<?php

	// 正确:
	$db = new Database;

	// 错误:
	$db = new Database();

#### 函数和方法

函数尽量全小写，并使用下划线分割单词:

	<?php

	function drink_beverage($beverage)
	{

#### 变量

所有变量尽量全小写，并使用下划线分割单词而不是驼峰:

	<?php

	// 正确:
	$foo = 'bar';
	$long_example = 'uses underscores';

	// 错误:
	$weDontWantThis = 'understood?';

### 缩进

代码在逻辑上缩进使用制表符(TAB)代替空格。


Vertical spacing (for multi-line) is done with spaces. Tabs are not good for vertical alignment because different people have different tab widths.

	<?php

	$text = 'this is a long text block that is wrapped. Normally, we aim for '
		  . 'wrapping at 80 chars. Vertical alignment is very important for '
		  . 'code readability. Remember that all indentation is done with tabs,'
		  . 'but vertical alignment should be completed with spaces, after '
		  . 'indenting with tabs.';

### 字符串连接

不要在连接符左右使用空格:

	<?php

	// 正确:
	$str = 'one'.$var.'two';

	// 错误:
	$str = 'one'. $var .'two';
	$str = 'one' . $var . 'two';

### Single Line Statements

Single-line IF statements should only be used when breaking normal execution (e.g. return or continue):

	<?php

	// Acceptable:
	if ($foo == $bar)
		return $foo;

	if ($foo == $bar)
		continue;

	if ($foo == $bar)
		break;

	if ($foo == $bar)
		throw new Exception('You screwed up!');

	// Not acceptable:
	if ($baz == $bun)
		$baz = $bar + 2;

### Comparison Operations

Please use OR and AND for comparison:

	<?php

	// Correct:
	if (($foo AND $bar) OR ($b AND $c))

	// Incorrect:
	if (($foo && $bar) || ($b && $c))
	if/else Blocks
	Please use elseif, not else if:

	<?php

	// Correct:
	elseif ($bar)

	// Incorrect:
	else if($bar)

### Switch structures

Each case, break and default should be on a separate line. The block inside a case or default must be indented by 1 tab.

	<?php

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

### Parentheses

There should be one space after statement name, followed by a parenthesis. The ! (bang) character must have a space on either side to ensure maximum readability. Except in the case of a bang or type casting, there should be no whitespace after an opening parenthesis or before a closing parenthesis.

	<?php

	// Correct:
	if ($foo == $bar)
	if ( ! $foo)

	// Incorrect:
	if($foo == $bar)
	if(!$foo)
	if ((int) $foo)
	if ( $foo == $bar )
	if (! $foo)

### Ternaries

All ternary operations should follow a standard format. Use parentheses around expressions only, not around just variables.

<?php

	$foo = ($bar == $foo) ? $foo : $bar;
	$foo = $bar ? $foo : $bar;

All comparisons and operations must be done inside of a parentheses group:

	<?php

	$foo = ($bar > 5) ? ($bar + $foo) : strlen($bar);

When separating complex ternaries (ternaries where the first part goes beyond ~80 chars) into multiple lines, spaces should be used to line up operators, which should be at the front of the successive lines:

	<?php

	$foo = ($bar == $foo)
		 ? $foo
		 : $bar;

### Type Casting

Type casting should be done with spaces on each side of the cast:

	<?php

	// Correct:
	$foo = (string) $bar;
	if ( (string) $bar)

	// Incorrect:
	$foo = (string)$bar;

When possible, please use type casting instead of ternary operations:

	<?php

	// Correct:
	$foo = (bool) $bar;

	// Incorrect:
	$foo = ($bar == TRUE) ? TRUE : FALSE;

When casting type to integer or boolean, use the short format:

	<?php

	// Correct:
	$foo = (int) $bar;
	$foo = (bool) $bar;

	// Incorrect:
	$foo = (integer) $bar;
	$foo = (boolean) $bar;

### Constants

Always use uppercase for constants:

	<?php

	// Correct:
	define('MY_CONSTANT', 'my_value');
	$a = TRUE;
	$b = NULL;

	// Incorrect:
	define('MyConstant', 'my_value');
	$a = True;
	$b = null;

Place constant comparisons at the end of tests:

	<?php

	// Correct:
	if ($foo !== FALSE)

	// Incorrect:
	if (FALSE !== $foo)

This is a slightly controversial choice, so I will explain the reasoning. If we were to write the previous example in plain English, the correct example would read:

	if variable $foo is not exactly FALSE

And the incorrect example would read:

	if FALSE is not exactly variable $foo

Since we are reading left to right, it simply doesn't make sense to put the constant first.

### Comments

#### One-line comments

Use //, preferably above the line of code you're commenting on. Leave a space after it and start with a capital. Never use #.

	<?php

	// Correct

	//Incorrect
	// incorrect
	# Incorrect

### Regular expressions

When coding regular expressions please use PCRE rather than the POSIX flavor. PCRE is considered more powerful and faster.

	<?php

	// Correct:
	if (preg_match('/abc/i'), $str)

	// Incorrect:
	if (eregi('abc', $str))

Use single quotes around your regular expressions rather than double quotes. Single-quoted strings are more convenient because of their simplicity. Unlike double-quoted strings they don't support variable interpolation nor integrated backslash sequences like \n or \t, etc.

	<?php

	// Correct:
	preg_match('/abc/', $str);

	// Incorrect:
	preg_match("/abc/", $str);

When performing a regular expression search and replace, please use the $n notation for backreferences. This is preferred over \\n.

	<?php

	// Correct:
	preg_replace('/(\d+) dollar/', '$1 euro', $str);

	// Incorrect:
	preg_replace('/(\d+) dollar/', '\\1 euro', $str);

Finally, please note that the $ character for matching the position at the end of the line allows for a following newline character. Use the D modifier to fix this if needed. [More info](http://blog.php-security.org/archives/76-Holes-in-most-preg_match-filters.html).

	<?php

	$str = "email@example.com\n";

	preg_match('/^.+@.+$/', $str);  // TRUE
	preg_match('/^.+@.+$/D', $str); // FALSE
