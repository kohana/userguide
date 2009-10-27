# Request Flow

Every application follows the same flow:

1. Application starts from `index.php`
2. Includes `APPPATH/bootstrap.php`
3. The bootstrap calls [Kohana::modules] with a list of modules to use
    1. Generates an array of paths for the cascading filesystem
    2. Checks each module to see if it has an init.php, and if it does, loads it
	    * Each init.php can define a series of routes to use, they are loaded when the init.php file is included
4. [Request::instance] called to process the request
    1. Checks each route until a match is found
    2. Loads controller and passes the request to it
    3. Calls the [Controller::before] method
    4. Calls the controller action
    5. Calls the [Controller::after] method
5. Displays the [Request] response

The controller action can be changed by [Controller::before] based on the request parameters.

[!!] Stub
