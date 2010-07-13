# Debuggen

Kohana heeft verschillende goede tools om je te helpen met het debuggen van je applicatie.

De meest gebruikte is [Kohana::debug]. Deze eenvoudige methode geef alle variablen terug, vergelijkbaar met [var_export](http://php.net/var_export) of [print_r](http://php.net/print_r), maar het gebruikt HTML voor extra opmaak.

    // Toon een dump van de variabelen $foo en $bar
    echo Kohana::debug($foo, $bar);

Kohana biedt ook een methode aan om de broncode van een bepaald bestand te tonen via [Kohana::debug_source].

    // Toon deze lijn van de broncode
    echo Kohana::debug_source(__FILE__, __LINE__);

Als je informatie wilt tonen over uw applicatie bestanden zonder te vertellen wat de installatie folder is, kan je [Kohana::debug_path] gebruiken:

    // Toont "APPPATH/cache" in plaats van het echte path
    echo Kohana::debug_path(APPPATH.'cache');
