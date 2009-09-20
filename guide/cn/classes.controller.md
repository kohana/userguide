#Controller

你的控制器都必须继承这个类。

##属性

###request

此属性会被赋值实例化后的 request 到构造器中。

##函数

###__construct()

参数传递一个实例化后的 [Request](classes.request) 类

此函数允许在控制器中像下面的格式调用实例化后的 request 类：

    $this->request

###before()

它会在控制器中所有 <code>action_</code> 方法之前执行。

###after()

它会在控制器中所有 <code>action_</code> 方法之后执行。