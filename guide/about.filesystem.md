# Cascading Filesystem

The Kohana filesystem is made up of a single directory structure that is mirrored in all directories along what we call the include path, which goes as follows:

1. application
2. modules, in order added
3. system

Files that are in directories higher up the include path order take precedence over files of the same name lower down the order, which makes it is possible to overload any file by placing a file with the same name in a "higher" directory:

![Cascading Filesystem Infographic](img/cascading_filesystem.png)

If you have a view file called layout.php in the application/views and system/views directories, the one in application will be returned when layout.php is searched for as it is highest in the include path order. If you then delete that file from application/views, the one in system/views will be returned when searched for.