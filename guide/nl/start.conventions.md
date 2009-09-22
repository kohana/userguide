# Conventions

## Class Names and File Location

Class names in Kohana follow a strict convention to facilitate [autoloading](start.autoloading).

Class names should have uppercase first letters with underscores to separate words. Underscores are significant as they directly reflect the file location in the filesystem.

	Class						File
	
	Controller_Template			classes/controller/template.php
	Model_User					classes/model/user.php
	Model_Auth_User				classes/model/auth/user.php
	Auth						classes/auth.php

CamelCased classnames should not be used.

All class file names and directory names are lowercase.

All classes should be in the `classes` directory. This may be at any level in the [cascading filesystem](start.filesystem).

Kohana 3 does not differentioate between *types* of class in the same way that Kohana 2.x and some other frameworks do. There is no distinction between a 'helper' or a 'library' class - in Kohana 3 any class can implement whater interface it like whether it be entirely static (helper-like), or instantiable, or a mixture (e.g. singleton).

## Code Style

It is encouraged to follow Kohana's coding style. This uses [BSD/Allman style](http://en.wikipedia.org/wiki/Indent_style#BSD.2FAllman_style) bracing. (There is a more [thorough discription](http://dev.kohanaphp.com/wiki/kohana2/CodingStyle) of Kohana's prefered style)