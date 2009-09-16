# Debugging

Kohana includes several powerful tools to help you debug your application.

The most basic of these is [Kohana::debug]. This simple method will display any number of variables, similar to [var_export] or [print_r], but using HTML for extra formatting.

~~~
// Display a dump of the $foo and $bar variables
echo Kohana::debug($foo, $bar);
~~~

Kohana also provides a method to show the source code of a particular file using [Kohana::debug_source].

~~~
// Display this line of source code
echo Kohana::debug_source(__FILE__, __LINE__);
~~~

If you want to display information about your application files without exposing the installation directory, you can use [Kohana::debug_path]:

~~~
// Displays "APPPATH/cache" rather than the real path
echo Kohana::debug_file(APPPATH.'cache');
~~~
