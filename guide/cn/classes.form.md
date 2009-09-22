#Form

所有生成的标签都是 XHTML。

##函数

###::open() {#open}

返回一个表单开始标签。默认没有传递参数时会把动作（action）提交到当前 URI。第二个参数可以其 <form> 标签添加额外的字符串属性（可选）。

    Form::open();             // <form method="[current URI]" accept-charset="[default charset]" method="post">
    Form::open('user/login'); // <form method="/user/login" accept-charset="[default charset]" method="post">
    Form::open(NULL, array(
        'method'=>'get'
    ));                       // <form method="[current URI]" accept-charset="[default charset]" method="get">

	// 译者注：
	// current URI = 当前 URI 地址
	// default charset = 默认字体编码
	
默认编码定义在 [Kohana::$charset](classes.kohana#$charset).

###::close() {#close}

创建一个表单的关闭标签（配合 open() 方法使用）：

    Form::close(); //</form>

###::input() {#input}

返回一个输入域。默认类型为文本。

    Form::input($name, $value = NULL, array $attributes = NULL);

###::hidden() {#hidden}

返回一个隐藏类型的文本域。它也属于上面的 <code>input()</code> 输入标签，只是设定其类型为 hidden。

###::password() {#password}

返回一个秘密输入域。语法和 <code>input()</code> 等同。

###::file() {#file}

返回一个定义输入字段和 "浏览"按钮，供文件上传的输入域。

###::checkbox() {#checkbox}

返回一个复选框。

    Form::checkbox($name, $value = NULL, $checked = FALSE, array $attributes = NULL);

###::radio() {#radio}

返回一个单选框。语法和上面的 <code>checkbox()</code> 等同。

###::textarea() {#textarea}

返回一个文本域。

    Form::textarea($name, $body = '', array $attributes = NULL, $double_encode = TRUE);

如果 $double_encode 设置为 true，那么 HTML 字符已经被编码 (<code>&amp;amp;</code>) 后会再次被编码 (<code>&amp;amp;amp;</code>)。

###::select() {#select}

返回一个下拉列表。

    Form::select($name, array $options = NULL, $selected = NULL, array $attributes = NULL);

1. 如果有选项请设置 <code>$options</code>。它是以这样的数组形式： <code>value => title</code> 配对。它也有可能包括另外的选项数组插入一个选项组之中。
2. <code>$selected</code> 应该设置一个默认情况下选中的选项名。

这里有两个实例。

    Form::select('example', array(
        'val_1'=>'Option 1',
        'val_2'=>'Option 2'
    ), 'val_1');
    
    /* HTML 输出代码：
    <select name="example">
    <option value="val_1" selected="selected">Option 1</option>
    <option value="val_2">Option 2</option>
    </select>*/
    
    Form::select('example', array(
        'Group 1'=>array(
            'val_1_1'=>'Group 1, Option 1',
            'val_1_2'=>'Group 1, Option 2'
        ),
        'Group 2'=>array(
            'val_2_1'=>'Group 2, Option 1'
        )
    ));
    
    /* HTML 输出代码：
    <select name="example">
    <optgroup label="Group 1">
    <option value="val_1_1">Group 1, Option 1</option>
    <option value="val_1_2">Group 1, Option 2</option>
    </optgroup>
    <optgroup label="Group 2">
    <option value="val_2_1">Group 2, Option 1</option>
    </optgroup>
    </select>*/
    
###::sumbit() {#submit}

类似于 <code>file()</code>，但是它的类型是 submit（提交）。

###::button() {#button}

创建一个按钮。其中 <code>$body</code> *无法*为图像等进行转义。

    Form::button($name, $body, array $attributes = NULL);

###::label() {#label}

创建一个为 <code>&lt;input&gt;</code> 元素定义同名 <code>$input</code> 的标注（标记）标签。如果没有文字指定，<code>$input</code> 将会使用 <code>_</code> 来代替空格。

    Form::label($input, $text = NULL, array $attributes = NULL);
