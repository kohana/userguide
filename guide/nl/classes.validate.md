#Validate
**todo: get filter() to work**

The static functions, except <code>::factory()</code>, are to be used as validation rules.

##Functions

###::factory()

Accepts an array, passes it to the constructor to create a new instance and returns it.

    Validate::factory($_POST);

Chainable.

###::not_empty()

Determines if a variable is empty.

###::regex()

Checks a variable with a regular expression.

###::min_length()

Checks that a variable is long enough using the UTF-8 functions.

###::max_length()

Checks that a variable is short enough (also uses the UTF-8 functions).

###::exact_length()

Checks that a variable is exactly the right length using the UTF-8 functions.

###::email()

Checks that a variable is a valid email address. Has an optional second parameter (<code>bool</code>) to check if the address conforms strictly to the (RFC822)[http://www.w3.org/Protocols/rfc822/] standard. This defaults to <code>FALSE</code>.

###::email_domain()

Checks if the domain of the email address has a valid MX record.

###::url()

Checks if the URL is valid.

###::ip()

Validates an IP address. Has an optional second parameter (<code>bool</code>) to allow private IP addresses. The default is <code>TRUE</code>.

###::credit_card()

Checks if a credit card number is valid according to the [Luhn algorithm](http://en.wikipedia.org/wiki/Luhn_algorithm). The second parameter denotes the type of card which is used to get the information form the config file.

###::phone()

Checks if a phone number is valid. The second parameter is optional and is an array of acceptable lengths. It defaults to

    array(7,10,11);

###::date()

Checks if a date is valid.

###::alpha()

Checks if a string only contains alphabetical characters. The second parameter, if <code>TRUE</code> enables UTF-8 compatibility. This defaults to <code>FALSE</code>.

###::alpha_numeric()

This is the same as <code>alpha()</code> but allows numbers too.

###::alpha_dash()

This is the same as <code>alpha_numeric()</code> but allows dashes (-) and underscodes (_) too.

###::digit()

This checks if a variable contains only numbers (no dots or dashes). It has the same UTF-8 parameter as above.

###::numeric()

Checks if a variable is a number (includes negative numbers and decimals). It can accept alternative decimal points depending on the locale.

###::range()

Checks if a number is within a given range.

    Validate::range($number, $min, $max);

###::decimal()

Checks the format of a decimal number. It also accepts alternative decimal points.

    Validate::decimal($number); //default number of decimal places is 2
    Validate::decimal($number, 6); //expects 6 decimal places
    Validate::decimal($number, array(6)); //same as above
    Validate::decimal($number, array(4, 2)); //expects four digits before the decimal place and 2 after

###::color()

Checks if a variable is a hexadecimal color. This can include or exclude the <code>#</code> and can accept the short notation of 3 characters.

###__construct()

This creates a new instance of the class and expects an array of <code>name => value</code> pairs like in <code>$_POST</code> and <code>$_GET</code> to be validated.

    $validate = new Validate($_POST);

###label()

This sets or overwrites a label for a field.

Chainable.

###filter()

This sets, appends or overwrites a filter for a field. The second parameter is the function to be called. It accepts static methods, <code>array($instance, 'method')</code> or a string. Any extra parameters to be passed to the function are passed as an <code>array</code>. To apply a filter, rule or callback to every variable, use <code>TRUE</code> as the field name.

    $validate->filter('field', 'htmlspecialchars');

Chainable.

###rule()

This sets, appends or overwrites a rule for a field. Each rule is executed once for each field. Each function or static method should be represented by a <code>string</code>. This example allows the username field to be not empty and no shorter than four characters.

    $validate->rule('username', 'not_empty')
             ->rule('username', 'min_length', array(4));

Chainable.

###rules()

This is the same as the method above but takes an array of rules for a field.

    $validate->rules('username', array(
        'not_empty'=>NULL,
        'min_length'=>array(4)
    ));

Chainable.

###callback()

This adds a callback to a field. No parameters can be passed to the callback method or function.

    $validate->callback('username', array($this, 'check_username_exists'));

Chainable.

###callbacks()

This is the same as <code>callback()</code> above except that it takes an array of callbacks for each field like <code>rules()</code>

Chainable.

###check()

This executes all filters, rules and callbacks. It returns <code>TRUE</code> on success or <code>FALSE</code on failure.

    if($validate->check() === true)
    {
        //do something
    }

###error()

This adds an error message to a field.

    $validate->error($field, $error, array $params = NULL);

###errors()

This returns an array of error messages after running <code>check()</code>. It will get the errors from a file specified. If <code>$file</code> is <code>NULL</code> then it will return the name of the field that has the error.

    $validate->errors($file = NULL, $translate = TRUE);

    $errors = $validate->errors('forms/login'); // checks messages/forms/login.php

It checks the file in the following order:

1. field/rule
2. field/default

If these fail, it will return file/field/rule for the field.

If <code>$translate</code> is a string, it is translated using [<code>__()</code>](classes.i18n#__()).

###matches()

This checks if the value of one field matches the value of another.