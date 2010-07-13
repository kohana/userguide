# Cross-Site Scripting (XSS) Veiligheid

De eerste stap om [XSS](http://wikipedia.org/wiki/Cross-Site_Scripting)-aanvallen te voorkomen is weten wanneer je jezelf moet beschermen. XSS kan enkel worden geactiveerd wanneer het wordt weergegeven in de HTML-inhoud, dit kan soms via een formulier-veld of worden getoond van database resultaten. Elke globale variabele dat gebruikersgegevens bevat kan worden aangetast. Dit omvat `$ _GET`, `$ _POST` en `$ _COOKIE` gegevens.

## Het voorkomen

Er zijn maar een paar eenvoudige regels te volgen om uw applicatie HTML te beschermen tegen XSS. De eerste stap is om de [Security::xss] methode te gebruiken om alle ingevoerde gegevens op te kuisen die afkomstig zijn van een globale variabele. Als je geen HTML wilt in een variable, gebruik dan [strip_tags](http://php.net/strip_tags) om alle ongewenste HTML tags te verwijderen van de ingevoerde waarde.

[!!] Als je gebruikers toelaat om HTML in te voeren in je applicatie, dan is het streng aanbevolen om een HTML "opkuis-tool" te gebruiken zoals [HTML Purifier](http://htmlpurifier.org/) of [HTML Tidy](http://php.net/tidy).

De tweede stap is om altijd de ingevoerde HTML te escapen. De [HTML] class voorziet generatoren voor veelvoorkomende tags, zo ook script en stylesheet links, ankers, afbeeldingen en e-mail (mailto) links. Elke niet-vertrouwde inhoud moet worden ge-escaped met [HTML::chars].

## Referenties

* [OWASP XSS Cheat Sheet](http://www.owasp.org/index.php/XSS_(Cross_Site_Scripting)_Prevention_Cheat_Sheet)
