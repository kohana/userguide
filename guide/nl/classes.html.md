#HTML

Dit is een statische klasse om te helpen met HTML.

##Variabelen

###::$attribute_order

Dit is een array met de volgorde waarin je je HTML attributen wilt hebben.

###::$windowed_urls

Dit is een <code>boolean</code>, als hij op <code>TRUE</code> staat zal een externe url in een nieuw scherm opengaan. Staat standaard op <code>FALSE</code>

##Functies

###::chars()

Dit zal speciale karakters naar HTML entiteiten omvormen. De tweede parameter, <code>$double_encode</code> is standaard <code>TRUE</code>.

    HTML::chars('©'); // wordt &copy;
    HTML::chars('&copy'); // wordt &amp;copy;
    HTML::chars('&copy', FALSE); // blijft hetzelfde

###::anchor()

Dit zal een link teruggeven (<code>$lt;a$gt;</code>).

    HTML::anchor($uri, $title = NULL, array $attributes = NULL, $protocol = NULL);

Als er geen <code>$title</code> is gespecifiëerd dan zal <code>$uri</code> gebruikt worden. <code>$title</code> wordt niet gefilterd op html.

    HTML::anchor('', 'Home page'); // <a href="/">Home page</a>
    HTML::anchor('link_uri'); // <a href="/link_uri">link_uri</a>
    HTML::anchor('link_uri', 'Link title'); // <a href="/link_uri">Link title</a>
    HTML::anchor('#importantHeading', '<b>Important</b> Heading'); // <a href="#importantHeading"><b>Important</b> Heading</a>

###::file_anchor()

Dit is hetzelfde als <code>anchor()</code> maar het verwijst naar een bestand in plaats van een pagina.

###::email()

Dit zal een e-mailadres versleutelen. Het zal er hetzelfde uitzien in de browser.

    HTML::email('test@example.com'); //&#x74;est&#x40;&#101;&#x78;&#x61;&#x6d;&#x70;l&#101;&#46;&#x63;o&#109;

###::mailto()

Dit zal een versleutelde e-maillink geven. <code>$title</code> wordt niet geëncodeerd en is standaard hetzelfde als $email.

    HTML::mailto($email, $title = NULL, array $attributes = NULL);
    HTML::mailto('test', 'Email me'); // <a href="&#109;&#097;&#105;&#108;&#116;&#111;&#058;&#116;e&#115;&#x74;">&#116;e&#115;&#x74;</a>

###::style()

Dit zal een <code>$lt;link&gt;</code> element geven om te gebruiken met een stylesheet.

    HTML::style($file, array $attributes = NULL, $index = FALSE);
    HTML::style('styles.css'); // <link type="text/css" href="/Kohana3/styles.css" rel="stylesheet" />

De laatste parameter bepaald of de indexpagina moet worden toegevoegd aan de url.

    HTML::style('styles.css', NULL, TRUE); //<link type="text/css" href="/Kohana3/index.php/styles.css" rel="stylesheet" />

###::script()

Dit is hetzelfde als <code>style()</code> behalve dat het een <code>$lt;script&gt;</code> tag maakt.

###::image()

Dit is ongeveer hetzelfde als <code>style()</code> en <code>script()</code>. Het maakt een <code>&lt;img&gt;</code> tag en heeft geen <code>$index</code> parameter.

###::attributes()

Dit geeft en string van html attributen van een array bestaande uit <code>attribuut => waarde</code> paren met een spatie vooraf.

    HTML::attributes(array(
        'title'=>'A title',
        'href'=>'http://example.com'
    )); //  href="http://example.com" title="A title"
