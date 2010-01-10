# Cross-Site Scripting (XSS)

La première étape pour se prémunir des attaques de type [XSS](http://wikipedia.org/wiki/Cross-Site_Scripting) est de savoir quand il faut le faire. Les attaques XSS ne peuvent être déclenchées que lors de l'affichage de contenu HTML au travers de formulaires ou de données issues de la base de données. Toute variable globale contenant des informations clientes peut être un vecteur d'attaques XSS. Cela inclut les données `$_GET`, `$_POST`, et `$_COOKIE`.

## Prévention

Il existe des règles simples à suivre pour prémunir vos applications de ces attaques. 

La première est d'utiliser systématiquement la méthode [Security::xss] pour nettoyer des données d'une variable globale. De plus si vous ne souhaitez pas avoir de HTML dans vos variables, utilisez la méthode [strip_tags](http://php.net/strip_tags) pour supprimer les balises HTML.

[!!] Si vous autorisez les utilisateurs à entrer des données HTML dans votre application, il est vivement recommandé d'utiliser une librairie de nettoyage HTML comme [HTML Purifier](http://htmlpurifier.org/) ou [HTML Tidy](http://php.net/tidy).

La seconde est de toujours échapper les données insérées dans vos pages HTML. La classe [HTML] fournit des générateurs pour de nombreuses balises HTML, incluant scripts et feuilles de style, liens, ancres, images et email. Tout contenu sans confiance doit être échappé avec [HTML::chars].

## Références

* [OWASP XSS Cheat Sheet](http://www.owasp.org/index.php/XSS_(Cross_Site_Scripting)_Prevention_Cheat_Sheet)
