# 系统安装

1. 从 [Kohana 官方网站](http://kohanaphp.com/)下载最新**稳定**版本的框架
2. 创建一个名为 'kohana' 的目录并解压缩到这个目录
3. 上传到这个目录的所有文件到你的服务器上
4. 编辑 `application/bootstrap.php` 文件并按实际情况修改下面配置：
	- 为你的程序设置默认[时区](http://php.net/timezones)
	- 在 [Kohana::init] 方法中设置 `base_url` 的值为 kohana 目录的路径（或域名地址）
6. 确保 `application/cache` 目录和 `application/logs` 目录为可写属性，命令为 `chmod application/{cache,logs} 0777`
7. 在你喜欢的浏览器地址栏中输入 `base_url` 来测试 Kohana 是否安装成功

[!!] 根据系统平台的不同，安装的目录可能会随着解压缩而失去原先的权限属性。如果有错误发生请在 Kohana 根目录设定所有文件属性为 755。命令为：`find . -type d -exec chmod 0755 {} \;`

如果你可以看到安装页面（install.php）则说明已经安装成功（一片绿色），如果它报告有任何的错误（红色显示），你应该在立刻修复。

![安装页面](img/install.png "Example of install page")

一旦安装页面报告你的环境确认无误，并且可以改名或删除`install.php`（Your environment is set up correctly you need to either rename or delete `install.php`）。安装刚才的说明操作之后就能看到 Kohana 的欢迎界面了(?? 目前 KO3 版本只有一个 'Hello World!' ??)

