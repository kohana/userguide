# 系统安装

1. 从 [Kohana 官方网站](http://kohanaframework.org/)下载最新**稳定**版本的框架
2. 创建一个名为 'kohana' 的目录并解压缩到这个目录
3. 上传到这个目录的所有文件到你的服务器上
4. 编辑 `application/bootstrap.php` 文件并按实际情况修改下面配置：
	- 为你的程序设置默认[时区](http://php.net/timezones)
	- 在 [Kohana::init] 方法中设置 `base_url` 的值为 kohana 目录的路径（或域名地址）
6. 确保 `application/cache` 目录和 `application/logs` 目录让服务器可写权限
7. 在你喜欢的浏览器地址栏中输入 `base_url` 来测试 Kohana 是否安装成功

[!!] 根据系统平台的不同，安装的目录可能会随着解压缩而失去原先的权限属性。如果有错误发生请在 Kohana 根目录设定所有文件属性为 755。命令为：`find . -type d -exec chmod 0755 {} \;`

如果你可以看到安装页面（install.php）则说明已经安装成功（一片绿色），如果它报告有任何的错误（红色显示），你应该在立刻修复。

![安装页面](img/install.png "Example of install page")

一旦安装页面报告你的环境确认无误，并且可以改名或删除在跟目录的 `install.php` 文件，然后你就能看到 Kohana 的欢迎界面：

![欢迎界面](img/welcome.png "Example of welcome page")


## 设置产品(Production)环境

在转移到产品环境之前有些事情需要完成:

1. 查看文档的[配置页面](about.configuration)。
   它涵盖了大多数的环境全局设置。
   一般来讲，在产品环境下需要开启缓存并关闭概况分析(profiling)([Kohana::init] 设置)。
   如果设置了很多路由，路由缓存也是很有必要的。
2. 在 application/bootstrap.php 捕获所有的异常，已保证敏感信息不会被堆栈跟踪泄漏。 
   下面有一个从 Shadowhand 的 wingsc.com 网站源代码提取出来的样例。
3. 打开 APC 或某些类型的指令缓存。
   这是最简单容易的提升 PHP 自身性能的方法。程序越复杂，使用指令缓存带来越大的利益。

		/**
		 * Set the environment string by the domain (defaults to 'development').
		 */
		Kohana::$environment = ($_SERVER['SERVER_NAME'] !== 'localhost') ? Kohana::PRODUCTION : Kohana::DEVELOPMENT;
		/**
		 * Initialise Kohana based on environment
		 */
		Kohana::init(array(
			'base_url'   => '/',
			'index_file' => FALSE,
			'profile'    => Kohana::$environment !== Kohana::PRODUCTION,
			'caching'    => Kohana::$environment === Kohana::PRODUCTION,
		));
		
		/**
		 * Execute the main request using PATH_INFO. If no URI source is specified,
		 * the URI will be automatically detected.
		 */
		$request = Request::instance($_SERVER['PATH_INFO']);
		
		try
		{
			// Attempt to execute the response
			$request->execute();
		}
		catch (Exception $e)
		{
			if ( Kohana::$environment == 'development' )
			{
				// Just re-throw the exception
				throw $e;
			}
		
			// Log the error
			Kohana::$log->add(Kohana::ERROR, Kohana::exception_text($e));
		
			// Create a 404 response
			$request->status = 404;
			$request->response = View::factory('template')
			  ->set('title', '404')
			  ->set('content', View::factory('errors/404'));
		}
		
		if ($request->send_headers()->response)
		{
			// Get the total memory and execution time
			$total = array(
			  '{memory_usage}' => number_format((memory_get_peak_usage() - KOHANA_START_MEMORY) / 1024, 2).'KB',
			  '{execution_time}' => number_format(microtime(TRUE) - KOHANA_START_TIME, 5).' seconds');
			
			// Insert the totals into the response
			$request->response = str_replace(array_keys($total), $total, $request->response);
		}
		
		
		/**
		 * Display the request response.
		 */
		echo $request->response;

