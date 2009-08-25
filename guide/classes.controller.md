#Controller

Your controllers should extend this class.

##Properties

###request

This will store the request instance that is passed to the constructor.

##Functions

###__construct()

The parameter passed is an instance of [Request](classes.request)

This function allows the request instance for the controller to be accessed like

    $this->request

###before()

This is executed before the <code>action_</code> method in your controller.

###after()

This is executed after the <code>action_</code> method in your controller.