# Error/Exception Handling

Kohana biedt zowel een exception handler als een error handler aan die errors transformeert in exceptions met behulp van PHP's [ErrorException](http://php.net/errorexception) class. Veel details over de error en de interne toestand van de applicatie wordt weergegeven door de handler:

1. Exception class
2. Error niveau
3. Error bericht
4. Bron van de error, met de errorlijn gehighlight
5. Een [debug backtrace](http://php.net/debug_backtrace) van de uitvoerings flow
6. Ingeladen bestanden, extensies en globale variablen

## Voorbeeld

Klik op een van de links om extra informatie te tonen:

<div>{{userguide/examples/error}}</div>

## Error/Exception Handling uitzetten

Als je niet de interne error handling wilt gebruiken, kan je deze uitschakelen wanneer je [Kohana::init] aanroept:

    Kohana::init(array('errors' => FALSE));
