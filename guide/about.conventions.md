# Conventions

It is encouraged to follow Kohana's [coding style](http://dev.kohanaframework.org/wiki/kohana2/CodingStyle). This uses [BSD/Allman style](http://en.wikipedia.org/wiki/Indent_style#BSD.2FAllman_style) bracing, among other things.

## Class Names and File Location {#classes}

Class names in Kohana follow a strict convention to facilitate [autoloading](using.autoloading). Class names should have uppercase first letters with underscores to separate words. Underscores are significant as they directly reflect the file location in the filesystem.

The following conventions apply:

1. CamelCased class names should not be used, except when it is undesirable to create a new directory level.
2. All class file names and directory names are lowercase.
3. All classes should be in the `classes` directory. This may be at any level in the [cascading filesystem](about.filesystem).

[!!] Unlike Kohana v2.x, there is no separation between "controllers", "models", "libraries" and "helpers". All classes are placed in the "classes/" directory, regardless if they are static "helpers" or object "libraries". You can use whatever kind of class design you want: static, singleton, adapter, etc.

## Examples

Remember that in a class, an underscore means a new directory. Consider the following examples:

Class Name            | File Path
----------------------|-------------------------------
Controller_Template   | classes/controller/template.php
Model_User            | classes/model/user.php
Database              | classes/database.php
Database_Query        | classes/database/query.php
Form                  | classes/form.php
