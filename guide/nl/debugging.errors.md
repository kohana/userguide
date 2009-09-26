# Error/Exception Handling

Kohana provides both an exception handler and an error handler that transforms errors into exceptions using PHP's [ErrorException](http://php.net/errorexception) class. Many details of the error and the internal state of the application is displayed by the handler:

1. Exception class
2. Error level
3. Errror message
4. Source of the error, with the error line highlighted
5. A [debug backtrace](http://php.net/debug_backtrace) of the execution flow
6. Included files, loaded extensions, and global variables

## Example

Click any of the links to toggle the display of additional information:

<div>{{userguide/examples/error}}</div>

## Disabling Error/Exception Handling

If you do not want to use the internal error handling, you can disable it when calling [Kohana::init]:

~~~
Kohana::init(array('errors' => FALSE));
~~~