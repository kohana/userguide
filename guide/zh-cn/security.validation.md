# 效验

使用 [Validate] 类可以对任意的数组进行校验。标签，过滤器，规则和回调函数都以数组的键（称之为 "字段名"）附属于 Validate 对象。

标签(labels)
:  标签是人们可读取的字段名。

过滤器(filters)
:  过滤器必须在规则和回调函数之前调用执行做预处理。

规则(rules)
:  规则是用于检测字段并返回结果 `TRUE` 或 `FALSE`。
   如果返回 `FALSE`，其对于的错误会添加到字段中。

回调函数(callbacks)
:  回调函数是自定义函数，它可以访问整个校验对象。
   回调函数的返回值会被忽略，因此，当校验错误时回调函数必须手动的使用 [Validate::error] 添加错误到对象中。

如果想把添加的过滤器，规则或回调函数应用到所有的定义的字段需要设置字段名为 `TRUE` 。

**[Validate] 对象会移除所有未设置标签，过滤器，规则或回调函数的字段。以此防止未被验证的字段发生校验错误。**

使用 [Validate::factory] 方法创建校验对象:

    $post = Validate::factory($_POST);

[!!] 提示 `$post` 对象将会被用于本教程的其他实例中。

## 添加过滤器

所有的校验规则被定义为字段名，方法或函数(使用 [PHP callback](http://php.net/callback) 语法)以及数组形式的参数:

    $object->filter($field, $callback, $parameter);

### 实例

如果要转换 "username" 字段的值为全小写:

    $post->filter('username', 'strtolower');

如果要对所有字段移除左右所有空格:

    $post->filter(TRUE, 'trim');

## 添加规则

所有的校验规则被定义为字段名，方法或函数(使用 [PHP callback](http://php.net/callback) 语法)以及数组形式的参数:

    $object->rule($field, $callback, $parameter);

校验默认提供的规则:

规则名称                  | 函数
------------------------- |-------------------------------------------------
[Validate::not_empty]     | 值不能为空值
[Validate::regex]         | 值使用正则表达式匹配
[Validate::min_length]    | 值的最小长度
[Validate::max_length]    | 值的最大长度
[Validate::exact_length]  | 值的长度必须是这里指定的长度
[Validate::email]         | 值必须是 Email 地址
[Validate::email_domain]  | 检查 Email 的域是否存在
[Validate::url]           | 值必须是 URL
[Validate::ip]            | 值必须是 IP 地址
[Validate::phone]         | 值必须是电话号码
[Validate::credit_card]   | 值必须是信用卡号
[Validate::date]          | 值必须是日期(时间)
[Validate::alpha]         | 仅允许英文字母
[Validate::alpha_dash]    | 仅允许英文字母和连词符号(-)
[Validate::alpha_numeric] | 仅允许英文字母和数字
[Validate::digit]         | 仅允许整数
[Validate::decimal]       | 值必须是小数或浮点数
[Validate::numeric]       | 仅允许数字
[Validate::range]         | 值必须是某一范围内的值
[Validate::color]         | 值必须是有效的 HEX 颜色
[Validate::matches]       | 值必须匹配其他字段的值

### 实例

任何函数添加到 `Validate` 类都可以通过调用一个规则而不必指定 `Validate` 类:

    $post
        ->rule('username', 'not_empty')
        ->rule('username', 'regex', array('/^[a-z_.]++$/iD'))

        ->rule('password', 'not_empty')
        ->rule('password', 'min_length', array('6'))
        ->rule('confirm',  'matches', array('password'))

        ->rule('use_ssl', 'not_empty');

任何 PHP 可用的函数也可以当作规则。比如，如果我们要检测用户是否使用 SSL:

    $post->rule('use_ssl', 'in_array', array(array('yes', 'no')));

[!!] 注意：所有的参数类数组都必须在一个数组内！

所有其他自定义的规则也可以作为回调函数添加进来:

    $post->rule('username', array($model, 'unique_username'));

## 添加回调函数

所有的校验规则被定义为字段名，方法或函数(使用 [PHP callback](http://php.net/callback) 语法)以及数组形式的参数:

    $object->callback($field, $callback);

[!!] 不同的过滤器和规则，没有参数也可以传递到回调函数之中。

### 实例

如果用户的密码必须是哈希值，我们可以使用回调函数哈希其值：

    $post->callback('password', array($model, 'hash_password'));

假设 `$model->hash_password()` 方法是类似这样定义的：

    public function hash_password(Validate $array, $field)
    {
        if ($array[$field])
        {
            // 如果存在此字段进行哈希操作
            $array[$field] = sha1($array[$field]);
        }
    }

