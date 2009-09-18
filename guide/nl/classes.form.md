#Form

Alle tags worden gegenereerd als XHTML.

##Functies

###::open() {#open}

Dit returned een open-form tag. Indien je <code>NULL</code> doorgeeft als de action, de standaardwaarde, en de huidige URI zal gebruikt worden. De tweede parameter is een lijst van andere attributen en is optioneel.

    Form::open();             // <form method="[current URI]" accept-charset="[default charset]" method="post">
    Form::open('user/login'); // <form method="/user/login" accept-charset="[default charset]" method="post">
    Form::open(NULL, array(
        'method'=>'get'
    ));                       // <form method="[current URI]" accept-charset="[default charset]" method="get">

De standaard charset is gedefiniëerd in [Kohana::$charset](classes.kohana#$charset).

###::close() {#close}

Dit geeft de sluit-form tag.

    Form::close(); //</form>

###::input() {#input}

Dit geeft een input tag. Standaard is het type text.

    Form::input($name, $value = NULL, array $attributes = NULL);

###::hidden() {#hidden}

Dit returned een hidden input field. Het is hetzelfde als <code>input()</code> behalve dat het type hidden is.

###::password() {#password}

Dit returned een password input field. De syntax is hetzelfde als <code>input()</code>.

###::file() {#file}

Dit returned een input field met type file.

###::checkbox() {#checkbox}

Dit returned een input field met type checkbox.

    Form::checkbox($name, $value = NULL, $checked = FALSE, array $attributes = NULL);

###::radio() {#radio}

Dit returned een input field met type radio. De syntax is hetzelfde als <code>checkbox()</code>.

###::textarea() {#textarea}

Dit returned een textarea.

    Form::textarea($name, $body = '', array $attributes = NULL, $double_encode = TRUE);

Als $double_encode true is, dan zullen HTML entiteiten die al geëncodeerd zijn (<code>&amp;amp;</code>) opnieuw worden geëncodeerd (<code>&amp;amp;amp;</code>).

###::select() {#select}

Dit returned een select tag.

    Form::select($name, array $options = NULL, $selected = NULL, array $attributes = NULL);

1. <code>$options</code> moet, indien er opties zijn, een array zijn van <code>value=>title</code>. Dit mag een andere array van opties inhouden die moeten worden geplaatst in een option group.
2. <code>$selected</code> moet de optie naam inhouden die moet geselecteerd zijn als standaard.

Here are two examples.

    Form::select('example', array(
        'val_1'=>'Option 1',
        'val_2'=>'Option 2'
    ), 'val_1');
    
    /* Geeft:
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
    
    /* Geeft:
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

Dit is hetzelfde als <code>file()</code> met type submit.

###::button() {#button}

Dit maakt een button tag. De <code>$body</code> wordt *niet* gecontroleerd op afbeeldingen enz.

    Form::button($name, $body, array $attributes = NULL);

###::label() {#label}

Dit maakt een label tag, <code>$input</code> moet de naam zijn van de bijhorende <code>&lt;input&gt;</code>. Als er geen tekst is gespecifiëerd dan zal <code>$input</code> gebruikt worden en <code>_</code>'s vervangen worden door spaties.

    Form::label($input, $text = NULL, array $attributes = NULL);
