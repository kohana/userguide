#URL

这是一个静态类，它无法实例化。

##函数

###::base()

它返回的基 URL 是设置在 [bootstrap.php](basics.startup#bootstrap_php) 的相关值。设置第一个参数为 <code>TRUE</code> 返回值会包含带有 index.php 的路径。如果你想指定特定的协议请设置第二个参数，设置为真则使用当前协议。

    URL::base($index = FALSE, $protocol = FALSE);

基 URL 为相对路径的几个实例：(/Kohana/) 

    URL::base(); // /Kohana/
    URL::base(TRUE); // /Kohana/
    URL::base(TRUE, TRUE); // http://example.com/Kohana/index.php/
    URL::base(FALSE, 'ftp'); // ftp://127.0.0.1/Kohana/

带有绝对 URL 的实例：(example.com/Kohana/):

    URL::base(); //example.com/Kohana/
    URL::base(TRUE); //example.com/Kohana/index.php/

###