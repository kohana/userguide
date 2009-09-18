#URL

This is a static class and cannot be instantiated.

##Functions

###::base()

This returns the base URL of the application as set in [bootstrap.php](basics.startup#bootstrap_php). Set the first parameter to <code>TRUE</code> to include the index.php in the path. If you want to specify a protocol then pass it as the second parameter. Setting this to <code>TRUE</code> uses the current protocol.

    URL::base($index = FALSE, $protocol = FALSE);

With a relative base_url (/Kohana/):

    URL::base(); // /Kohana/
    URL::base(TRUE); // /Kohana/
    URL::base(TRUE, TRUE); // http://example.com/Kohana/index.php/
    URL::base(FALSE, 'ftp'); // ftp://127.0.0.1/Kohana/

With an absolute url (example.com/Kohana/):

    URL::base(); //example.com/Kohana/
    URL::base(TRUE); //example.com/Kohana/index.php/

###