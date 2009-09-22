#HTML

创建 HTML 标签的静态辅助函数。

##变量

###::$attribute_order

它是以数组形式列举的可用 HTML 属性集合。

###::$windowed_urls

设置外部链接是否使用新窗口打开。这是一个 <code>boolean</code> （布尔）型变量。默认为 <code>FALSE</code>。

##函数

###::chars()

转换特殊字符为 HTML 字符。默认第二个参数 <code>$double_encode</code> 为 <code>TRUE</code>。

    HTML::chars('©'); // 转换后： &copy;
    HTML::chars('&copy'); // 转换后： &amp;copy;
    HTML::chars('&copy', FALSE); // 不变

###::anchor()

返回一个 HTML 链接(<code>&lt;a&gt;</code>)。

    HTML::anchor($uri, $title = NULL, array $attributes = NULL, $protocol = NULL);

如果没有设置 <code>$title</code> 那么其值会被 <code>$uri</code> 代替。HTML 无法为 <code>$title</code> 转义。

    HTML::anchor('', 'Home page'); // <a href="/">Home page</a>
    HTML::anchor('link_uri'); // <a href="/link_uri">link_uri</a>
    HTML::anchor('link_uri', 'Link title'); // <a href="/link_uri">Link title</a>
    HTML::anchor('#importantHeading', '<b>Important</b> Heading'); // <a href="#importantHeading"><b>Important</b> Heading</a>

###::file_anchor()

它类似于 <code>anchor()</code> 只是它链接是文件而非页面。

###::email()

返回一个混淆后的 Email 地址，但是在浏览器显示的是正常的 Email 地址。

    HTML::email('test@example.com'); //&#x74;est&#x40;&#101;&#x78;&#x61;&#x6d;&#x70;l&#101;&#46;&#x63;o&#109;

###::mailto()

返回一个混淆后的 Email 链接。<code>$title</code> 默认为 Email 地址且无法转义。

    HTML::mailto($email, $title = NULL, array $attributes = NULL);
    HTML::mailto('test', 'Email me'); // <a href="&#109;&#097;&#105;&#108;&#116;&#111;&#058;&#116;e&#115;&#x74;">&#116;e&#115;&#x74;</a>

###::style()

返回一个使用样式表的 <code>&lt;link&gt;</code> 元素。

    HTML::style($file, array $attributes = NULL, $index = FALSE);
    HTML::style('styles.css'); // <link type="text/css" href="/Kohana3/styles.css" rel="stylesheet" />

最后一个参数决定是否在 URL 中包含 index.php。

    HTML::style('styles.css', NULL, TRUE); //<link type="text/css" href="/Kohana3/index.php/styles.css" rel="stylesheet" />

###::script()

类似于上面的 <code>style()</code> 方法，只是它创建的是一个 <code>&lt;script&gt;</code> 标签。

###::image()

类似于上面的 <code>style()</code> 和 <code>script()</code> 方法。它创建的是一个 <code>&lt;img&gt;</code> 标签且没有 <code>$index</code> 参数。

###::attributes()

返回一个由自定义 HTML 属性组成的元素空间，其中 HMTL 属性以 <code>attribute => value</code> 方式配对。

    HTML::attributes(array(
        'title'=>'A title',
        'href'=>'http://example.com'
    )); //  href="http://example.com" title="A title"
