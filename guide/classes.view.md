#View

Most of these functions are chainable.

##Functions

###::factory

This creates a new View instance and returns it. It can pass the <code>file</code> and <code>data</code> to the constructor.

    View::factory('file', $data_array);
    View::factory('file');
    View::factory();

###__construct()

This creates a new View instance and may specify a file and data to pass to it.

###set_filename()

Sets the name of the view file to load.

Chainable.

###set()

This is used to pass a variable to the view.

    $view->set('var_name', 'var_value');

It also accepts an array of <code>variable => value</code> pairs.

    $view->set(array(
        'title'=>'Title',
        'content'=>'Some page content here.'
    ));

Chainable.

###set_global()

This works the same as <code>set()</code> above but adds it to a global scope for all views. If a local variable (one set with <code>set()</code>) has the same name as a global one, the local variable will take precedence.

###bind()

This will pass variable to a view by reference. If the variable is changed then those changes will be altered in the view. This will also work the other way.

    $var = 'abc';
    $view->bind('var', $var);
    //in the view, $var = 'abc';
    
    $var = 'def';
    //in the view, $var = 'def';

Chainable.

###bind_global()

This does the same as <code>bind()</code> but makes it available to all views.

###render()

You can specify the file name of the view here too. This will return the processed view file as a string.

    $view->render('template');
