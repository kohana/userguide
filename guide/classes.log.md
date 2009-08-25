#Log

###Functions

##::instance()
Static function that returns the log instance. It will create it if it does not yet exist.

##attach()
This attaches a writer to the log class. This creates a new Kohana_Log_File instance with a parameter and attaches it to the log class. This allows the log writer to be informed of any logs to be written.

    Kohana::$log->attach(new Kohana_Log_File(APPPATH.'logs'));

The second parameter allows only certain types of messages to be logged to a writer. This optional and defaults to all.

##detach()
Stops a log writer from logging.

##add($type, $message)
This creates a new log message and gives it a timestamp.

    Kohana::$log->add(E_WARNING, 'Something went a little wrong');

##write()
This forces all logs to be written.
