# Validation

Validation can be performed on any array using the [Validate] class. Labels, filters, rules, and callbacks can be attached to a Validate object by the array key, called a "field name".

labels
:  A label is a human-readable version of the field name.

filters
:  A filter modifies the value of an field before rules and callbacks are run.

rules
:  A rule is a check on a field that returns `TRUE` or `FALSE`. If a rule
   returns `FALSE`, an error will be added to the field.

callbacks
:  A callback is custom method that can access the entire Validate object.  
   The return value of a callback is ignored. Instead, the the callback must
   manually add an error to the object using [Validate::error] on failure.

Using `TRUE` as the field name when adding a filter, rule, or callback will by applied to all named fields.

**The [Validate] object will remove all fields from the array that have not been specifically named by a label, filter, rule, or callback. This prevents access to fields that have not been validated as a security precaution.**

Creating a validation object is done using the [Validate::factory] method:

    $post = Validate::factory($_POST);

[!!] The `$post` object will be used for the rest of this tutorial.

## Adding Filters

All validation filters are defined as a field name, a method or function (using the [PHP callback](http://php.net/callback) syntax), and an array of parameters:

    $object->filter($field, $callback, $parameter);

### Examples

If we wanted to convert the "username" field to lowercase:

    $post->filter('username', 'strtolower');

If we wanted to remove all leading and trailing whitespace from all fields:

    $post->filter(TRUE, 'trim');

## Adding Rules

All validation rules are defined as a field name, a method or function (using the [PHP callback](http://php.net/callback) syntax), and an array of parameters:

    $object->rule($field, $callback, $parameter);

Validation also comes with several default rules:

Rule name                 | Function
------------------------- |-------------------------------------------------
[Validate::not_empty]     | Value must be a non-empty value
[Validate::regex]         | Match the value against a regular expression
[Validate::min_length]    | Minimum number of characters for value
[Validate::max_length]    | Maximum number of characters for value
[Validate::exact_length]  | Value must be an exact number of characters
[Validate::email]         | An email address is required
[Validate::email_domain]  | Check that the domain of the email exists
[Validate::url]           | Value must be a URL
[Validate::ip]            | Value must be an IP address
[Validate::phone]         | Value must be a phone number
[Validate::credit_card]   | Require a credit card number
[Validate::date]          | Value must be a date (and time)
[Validate::alpha]         | Only alpha characters allowed
[Validate::alpha_dash]    | Only alpha and hyphens allowed
[Validate::alpha_numeric] | Only alpha and numbers allowed
[Validate::digit]         | Value must be an interger digit
[Validate::decimal]       | Value must be a decimal or float value
[Validate::numeric]       | Only numeric characters allowed
[Validate::range]         | Value must be within a range
[Validate::color]         | Value must be a valid HEX color
[Validate::matches]       | Value matches another field value

### Examples

Any function added to the `Validate` class may be added as a rule without specifying the `Validate` class:

    $post
        ->rule('username', 'not_empty')
        ->rule('username', 'regex', array('/^[a-z_.]++$/i'))

        ->rule('password', 'not_empty')
        ->rule('password', 'min_length', array('6'))
        ->rule('confirm',  'matches', array('password'))

        ->rule('use_ssl', 'not_empty');

Any existing PHP function can also be used a rule. For instance, if we want to check if the user prefers SSL:

    $post->rule('use_ssl', 'in_array', array(array('yes', 'no')));

[!!] Note that all array parameters must still be wrapped in an array!

All other custom rules can be added with the complete callback name:

    $post->rule('username', array($model, 'unique_username'));

## Adding callbacks

All validation rules are defined as a field name and a method or function (using the [PHP callback](http://php.net/callback) syntax):

    $object->callback($field, $callback);

[!!] Unlike filters and rules, no parameters can be passed to a callback.

### Examples

The user password must be hashed if it validates, so we will hash it using a callback:

    $post->callback('password', array($model, 'hash_password'));

This would assume that the `$model->hash_password()` method would be defined similar to:

    public function hash_password(Validate $array, $field)
    {
        if ($array[$field])
        {
            // Hash the password if exists
            $array[$field] = sha1($array[$field]);
        }
    }

