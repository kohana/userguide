# Autoloading

Kohana takes advantage of PHP5's [autoloading feature](http://php.net/manual/en/language.oop5.autoload.php). This means that you don't need to (and shouldn't) ever `include()` or `require` files that are part of Kohana's normal directory structure.

To facilitate this, Kohana 3.0 has a [file naming convention](start.conventions) where the file name reflects it's location in the directory structure.

When a class is first used, Kohana looks for it in a particular file according to it's conventions. For example:

	$validate = Validate::factory($data);
	
When this code executes, there is no need to include ro require the validate class file as Kohana knows to look for it in a file called `validate.php`. It will look in the `application/classes` directory followed by the module and system directories as described in [the filesystem overview](general.filesystem).

Underscore characters in class names are converted into directory separators when looking for a class file. For examples of this see [Class Names and File Locations](start.conventions).