#Form

All generated tags are XHTML.

##Functions

###::open() {#open}

This returns an opening form tag. Pass <code>NULL</code>, the default, as the action and the current URI will be used. The second parameter specifies a list of additional attributes. This is optional.

    Form::open();             // <form method="[current URI]" accept-charset="[default charset]" method="post">
    Form::open('user/login'); // <form method="/user/login" accept-charset="[default charset]" method="post">
    Form::open(NULL, array(
        'method'=>'get'
    ));                       // <form method="[current URI]" accept-charset="[default charset]" method="get">

The default charset is defined [Kohana::$charset](classes.kohana#$charset).

###::close() {#close}

This closes a form tag.

    Form::close(); //</form>

###::input() {#input}

This returns an input tag. By default, the type set to text.

    Form::input($name, $value = NULL, array $attributes = NULL);

###::hidden() {#hidden}

This returns a hidden input field. It is identical to <code>input()</code> above except that it has the type set to hidden.

###::password() {#password}

This returns a password input field. Its syntax is identical to <code>input()</code>.

###::file() {#file}

This returns an input field with its type set to file.

###::checkbox() {#checkbox}

This returns an input field with its type set to checkbox.

    Form::checkbox($name, $value = NULL, $checked = FALSE, array $attributes = NULL);

###::radio() {#radio}

This returns an input field with its type set to radio. Its syntax is identical to <code>checkbox()</code> above.

###::textarea() {#textarea}

This returns a textarea tag.

    Form::textarea($name, $body = '', array $attributes = NULL, $double_encode = TRUE);

If $double_encode is true then HTML entities that are already encoded (<code>&amp;amp;</code>) will be encoded again (<code>&amp;amp;amp;</code>).

###::select() {#select}

This returns a select tag.

    Form::select($name, array $options = NULL, $selected = NULL, array $attributes = NULL);

1. <code>$options</code> should, if there are options, be an array of <code>value => title</code> pairs. This may include another array of options to be inserted in an option group.
2. <code>$selected</code> should contain the option name that should be selected by default.

Here are two examples.

    Form::select('example', array(
        'val_1'=>'Option 1',
        'val_2'=>'Option 2'
    ), 'val_1');
    
    /* Gives this:
    <select name="example">
    <option value="val_1" selected="selected">Option 1</option>
    <option value="val_2">Option 2</option>
    </select>*/
    
    Form::select('example', array(
        'Group 1'=>array(
            'val_1_1'=>'Group 1, Option 1',
            'val_1_2'=>'Group 1, Option 2'
        ),
        'Group 2'=>array(
            'val_2_1'=>'Group 2, Option 1'
        )
    ));
    
    /* Gives this:
    <select name="example">
    <optgroup label="Group 1">
    <option value="val_1_1">Group 1, Option 1</option>
    <option value="val_1_2">Group 1, Option 2</option>
    </optgroup>
    <optgroup label="Group 2">
    <option value="val_2_1">Group 2, Option 1</option>
    </optgroup>
    </select>*/
    
###::sumbit() {#submit}

This is the same as <code>file()</code> but its type is submit.

###::button() {#button}

This creates a button tag. The <code>$body</code> is *not* escaped for images etc.

    Form::button($name, $body, array $attributes = NULL);

###::label() {#label}

This creates a label tag <code>$input</code> should be the name of the corresponding <code>&lt;input&gt;</code>. If no text is specified then <code>$input</code> will be used and <code>_</code>s replaced with spaces.

    Form::label($input, $text = NULL, array $attributes = NULL);
