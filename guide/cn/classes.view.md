#View

大多数的函数都是可以串链使用。

##函数

###::factory

创建一个新的 View 实例化并返回。它可以传递 <code>file</code> （视图文件）和 <code>data</code> （数据）到构造器进行实例化。

    View::factory('file', $data_array);
    View::factory('file');
    View::factory();

###__construct()

创建一个新的 View 实例化同时可以设定视图文件并传递数据。

###set_filename()

设置要加载的视图文件名。

支持串链。

###set()

设置要传递到视图中的变量。

    $view->set('var_name', 'var_value');

也支持这样的数组形式：<code>variable => value</code>。

    $view->set(array(
        'title'=>'Title',
        'content'=>'Some page content here.'
    ));

支持串链。

###set_global()

功能和上面的 <code>set()</code> 函数相同，但它设置的变量是一个可适用于所有视图。如果一个局部变量（即使用 <code>set()</code> 设置的变量）和一个相同名称的全局变量，局部变量有最高优先权。

###bind()

它会传递一个引用变量到视图中。如果变量改变了相关联的视图文件的值也会改变。

    $var = 'abc';
    $view->bind('var', $var);
    //视图文件中，$var = 'abc';
    
    $var = 'def';
    //视图文件中，$var = 'def';

支持串链。

###bind_global()

功能和上面的 <code>bind()</code> 函数相同，但它设置的变量是一个可适用于所有视图。

###render()

此函数也支持指定视图文件名。它会返回一串经过处理后的视图文件的字符串。

    $view->render('template');
