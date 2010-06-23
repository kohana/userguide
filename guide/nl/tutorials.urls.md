# Routes, URLs, and Links

This section will provide you with the basic idea behind Kohana's request routing, url generation and links.

## Routing

As mentioned in the [Request Flow](about.flow) section, a request is handled by the [Request] class which finds a matching [Route] and loads the appropriate controller to handle the request. This system provides much flexibility as well as a common sense default behavior.

If you look in `APPPATH/bootstrap.php` you will see the following code which is run immediately before the request is handed off to [Request::instance]:

    Route::set('default', '(<controller>(/<action>(/<id>)))')
      ->defaults(array(
        'controller' => 'welcome',
        'action'     => 'index',
      ));

This sets the `default` route with a uri in the format of `(<controller>(/<action>(/<id>)))`. The tokens surrounded with `<>` are *keys* and the tokens surrounded with `()` are *optional* parts of the uri. In this case, the entire uri is optional, so a blank uri would match and the default controller and action would be assumed resulting in the `Controller_Welcome` class being loaded and eventually the `action_index` method being called to handle the request.

Notice that in Kohana routes, any characters are allowed aside from `()<>` and the `/` has no special meaning. In the default route the `/` is used as a static separator but as long as the regex makes sense there is no restriction to how you can format your routes.

### Directories

For organizational purposes you may wish to place some of your controllers in subdirectories. A common case is for an admin backend to your site:

    Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))')
      ->defaults(array(
        'directory'  => 'admin',
        'controller' => 'home',
        'action'     => 'index',
      ));

This route specifies that the uri must begin with `admin` to match and the directory is statically assigned to `admin` in the defaults. Now a request to `admin/users/create` would load the `Controller_Admin_Users` class and call the `action_create` method.

### Patterns

The Kohana route system uses perl compatible regular expressions in its matching process. By default the keys (surrounded by `<>`) are matched by `[a-zA-Z0-9_]++` but you can define your own patterns for each key by passing an associative array of keys and patterns as an additional argument to [Route::set]. To extend our previous example let's say you have an admin section and an affiliates section. You could specify those in separate routes or you could do something like this:

    Route::set('sections', '<directory>(/<controller>(/<action>(/<id>)))',
      array(
        'directory' => '(admin|affiliate)'
      ))
      ->defaults(array(
        'controller' => 'home',
        'action'     => 'index',
      ));
      
This would provide you with two sections of your site, 'admin' and 'affiliate' which would let you organize the controllers for each into subdirectories but otherwise work like the default route.

### More Route Examples

There are countless other possibilities for routes. Here are some more examples:

    /*
     * Authentication shortcuts
     */
    Route::set('auth', '<action>',
      array(
        'action' => '(login|logout)'
      ))
      ->defaults(array(
        'controller' => 'auth'
      ));
      
    /*
     * Multi-format feeds
     *   452346/comments.rss
     *   5373.json
     */
    Route::set('feeds', '<user_id>(/<action>).<format>',
      array(
        'user_id' => '\d+',
        'format' => '(rss|atom|json)',
      ))
      ->defaults(array(
        'controller' => 'feeds',
        'action' => 'status',
      ));
    
    /*
     * Static pages
     */
    Route::set('static', '<path>.html',
      array(
        'path' => '[a-zA-Z0-9_/]+',
      ))
      ->defaults(array(
        'controller' => 'static',
        'action' => 'index',
      ));
      
    /*
     * You don't like slashes?
     *   EditGallery:bahamas
     *   Watch:wakeboarding
     */
    Route::set('gallery', '<action>(<controller>):<id>',
      array(
        'controller' => '[A-Z][a-z]++',
        'action'     => '[A-Z][a-z]++',
      ))
      ->defaults(array(
        'controller' => 'Slideshow',
      ));
      
    /*
     * Quick search
     */
    Route::set('search', ':<query>', array('query' => '.*'))
      ->defaults(array(
        'controller' => 'search',
        'action' => 'index',
      ));

Routes are matched in the order specified so be aware that if you set routes after the modules have been loaded a module could specify a route that conflicts with your own. This is also the reason that the default route is set last, so that custom routes will be tested first.
      
### Request Parameters

The directory, controller and action can be accessed from the [Request] instance in either of these two ways:

    $this->request->action;
    Request::instance()->action;
    
All other keys specified in a route can be accessed from within the controller via:

    $this->request->param('key_name');
    
The [Request::param] method takes an optional second argument to specify a default return value in case the key is not set by the route. If no arguments are given, all keys are returned as an associative array.

### Convention

The established convention is to either place your custom routes in the `MODPATH/<module>/init.php` file of your module if the routes belong to a module, or simply insert them into the `APPPATH/bootstrap.php` file above the default route if they are specific to the application. Of course, they could also be included from an external file or even generated dynamically.
    
## URLs

Along with Kohana's powerful routing capabilities are included some methods for generating URLs for your routes' uris. You can always specify your uris as a string using [URL::site] to create a full URL like so:

    URL::site('admin/edit/user/'.$user_id);

However, Kohana also provides a method to generate the uri from the route's definition. This is extremely useful if your routing could ever change since it would relieve you from having to go back through your code and change everywhere that you specified a uri as a string. Here is an example of dynamic generation that corresponds to the `feeds` route example from above:

    Route::get('feeds')->uri(array(
      'user_id' => $user_id,
      'action' => 'comments',
      'format' => 'rss'
    ));

Let's say you decided later to make that route definition more verbose by changing it to `feeds/<user_id>(/<action>).<format>`. If you wrote your code with the above uri generation method you wouldn't have to change a single line! When a part of the uri is enclosed in parentheses and specifies a key for which there in no value provided for uri generation and no default value specified in the route, then that part will be removed from the uri. An example of this is the `(/<id>)` part of the default route; this will not be included in the generated uri if an id is not provided.

One method you might use frequently is the shortcut [Request::uri] which is the same as the above except it assumes the current route, directory, controller and action. If our current route is the default and the uri was `users/list`, we can do the following to generate uris in the format `users/view/$id`:

    $this->request->uri(array('action' => 'view', 'id' => $user_id));
    
Or if within a view, the preferable method is:

    Request::instance()->uri(array('action' => 'view', 'id' => $user_id));

## Links

[!!] links stub
