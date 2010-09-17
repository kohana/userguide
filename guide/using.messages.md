# Message Basics

Kohana messages are human friendly strings represented by a shorter word or phrase, called a "key". Messages are accessed using the [Kohana::message] method, which returns either an entire group of messages, or a single message.

As an example, when a user is not logged in and attempts to access a page that requires authentication, an error such as "You must be logged in to access this page" might be displayed. This message could be stored in the `auth` file with a `must_login` key:

    $message = Kohana::message('auth', 'must_login');

Messages are not translated. To translate a message, use the [translation function](using.translation):

    $translated = __(Kohana::message('auth', 'must_login'));

[!!] In Kohana v2, the message system was used for translation. However, it is highly recommended to use the new translation system instead of messages, as it provides readable text even when a translation is not available.

## Message Files

All message files are plain PHP files, stored in the `messages/` directory, that return an associative array:

    <?php defined('SYSPATH') or die('No direct script access.');

    return array(
        'must_login' => 'You must login to access this page',
        'no_access'  => 'You do not have privileges to access this page',
    );

Message files are similar to [config files](using.configuration#config-files) in that they are merged together. This means that all of the messages stored in a file called `auth` will be combined into a single array, so it is not necessary to duplicate all of the messages when you create a new `auth` file.
