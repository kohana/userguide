#HTML

This is a static class to help with creating html.

##Variables

###::$attribute_order

This is an array specifying the order that you would like your html attributes in.

###::$windowed_urls

Sets whether external urls automatically open in a new window. This is a <code>boolean</code> variable. <code>FALSE</code> by default.

##Functions

###::chars()

This will convert special characters to HTML entities. The second parameter, <code>$double_encode</code> is <code>TRUE</code> by default.

    HTML::chars('©'); // becomes &copy;
    HTML::chars('&copy'); // becomes &amp;copy;
    HTML::chars('&copy', FALSE); // stays the same.

###::anchor()

This will return a link (<code>&lt;a&gt;</code>).

    HTML::anchor($uri, $title = NULL, array $attributes = NULL, $protocol = NULL);

If no <code>$title</code> is specified then <code>$uri</code> will be used instead. HTML is not escaped for <code>$title</code>.

    HTML::anchor('', 'Home page'); // <a href="/">Home page</a>
    HTML::anchor('link_uri'); // <a href="/link_uri">link_uri</a>
    HTML::anchor('link_uri', 'Link title'); // <a href="/link_uri">Link title</a>
    HTML::anchor('#importantHeading', '<b>Important</b> Heading'); // <a href="#importantHeading"><b>Important</b> Heading</a>

###::file_anchor()

This is like <code>anchor()</code> but it links to a file instead of a page.

###::email()

This will obfuscate an email address although it displays the same in a browser.

    HTML::email('test@example.com'); //&#x74;est&#x40;&#101;&#x78;&#x61;&#x6d;&#x70;l&#101;&#46;&#x63;o&#109;

###::mailto()

Returns an obfuscated email link. <code>$title</code> is not escaped and defaults to the same as $email.

    HTML::mailto($email, $title = NULL, array $attributes = NULL);
    HTML::mailto('test', 'Email me'); // <a href="&#109;&#097;&#105;&#108;&#116;&#111;&#058;&#116;e&#115;&#x74;">&#116;e&#115;&#x74;</a>

###::style()

This returns a <code>&lt;link&gt;</code> element for use with a stylesheet.

    HTML::style($file, array $attributes = NULL, $index = FALSE);
    HTML::style('styles.css'); // <link type="text/css" href="/Kohana3/styles.css" rel="stylesheet" />

The last parameter determines if the index page should be added to the url.

    HTML::style('styles.css', NULL, TRUE); //<link type="text/css" href="/Kohana3/index.php/styles.css" rel="stylesheet" />

###::script()

This is the same as <code>style()</code> above except that it creates a <code>&lt;script&gt;</code> tag.

###::image()

This is similar to <code>style()</code> and <code>script()</code>. It creates a <code>&lt;img&gt;</code> tag and has no <code>$index</code> parameter.

###::attributes()

This returns a string of html attributes from an array of <code>attribute => value</code> pairs with a leading space.

    HTML::attributes(array(
        'title'=>'A title',
        'href'=>'http://example.com'
    )); //  href="http://example.com" title="A title"
