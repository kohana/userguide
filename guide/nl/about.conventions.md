# Conventies

Het is aanbevolen om Kohana's [manier van coderen](http://dev.kohanaframework.org/wiki/kohana2/CodingStyle) te gebruiken. Dit gebruikt de [BSD/Allman stijl](http://en.wikipedia.org/wiki/Indent_style#BSD.2FAllman_style) van haakjes, en nog andere dingen.

## Class namen en locaties van bestanden {#classes}

Class namen in Kohana volgen een strikte conventie om [autoloading](using.autoloading) gemakkelijker te maken. Class namen zouden met een hoofdletter moeten beginnen en een underscore gebruiken om woorden af te scheiden van elkaar. Underscores zijn belangrijk omdat ze de locatie van het bestand weerspiegelen in de folderstructuur.

De volgende conventies worden gebruikt:

1. CamelCased class namen worden niet gebruikt, alleen maar als het onnodig is om een nieuw folderniveau aan te maken.
2. Alle class bestandsnamen en foldernamen zijn met kleine letters geschreven.
3. Alle classes zitten in de `classes` folder. Dit kan op ieder niveau in het [cascading filesystem](about.filesystem).

[!!] In tegenstelling tot Kohana v2.x, is er geen afscheiding tussen "controllers", "models", "libraries" en "helpers". Alle classes worden in de folder "classes/" geplaatst, of het nu static "helpers" of object "libraries" zijn. Ieder design pattern is mogelijk voor het maken van classes: static, singleton, adapter, etc.

## Voorbeelden

Onthoud dat in een class, een underscore een folder betekent. Bekijk de volgende voorbeelden:

Class Naam            | Locatie File
----------------------|-------------------------------
Controller_Template   | classes/controller/template.php
Model_User            | classes/model/user.php
Database              | classes/database.php
Database_Query        | classes/database/query.php
Form                  | classes/form.php

## Coding Standaarden {#coding_standards}

Om zeer consistente broncode te schrijven, vragen we dat iedereen de coding standaarden zo nauw mogelijk probeert na te volgen.

### Gekrulde Haakjes (Brackets)

Gebruik aub [BSD/Allman Stijl](http://en.wikipedia.org/wiki/Indent_style#BSD.2FAllman_style) van bracketing.

### Naam conventies

Kohana gebruikt underscore namen, geen camelCase.

#### Classes

	<?php

	// Controller class, gebruikt Controller_ voorvoegsel
	class Controller_Apple extends Controller {

	// Model class, gebruikt Model_ voorvoegsel
	class Model_Cheese extends Model {

	// Regular class
	class peanut {

Wanneer je een instantie aanmaakt van een class, gebruik dan haakjes als je niets meegeeft aan de constructor:

	<?php

	// Correct:
	$db = new Database;

	// Niet correct:
	$db = new Database();

#### Functies en Methoden

Functies moeten altijd lowercase zijn. Gebruik underscores om woorden van elkaar te scheiden:

	<?php

	function drink_beverage($beverage)
	{

#### Variabelen

Alle variabelen moeten lowercase zijn, gebruik underscores, geen cameCase:

	<?php

	// Correct:
	$foo = 'bar';
	$long_example = 'Gebruik underscores';

	// Niet correct:
	$weWillenDitDusNiet = 'verstaan?';

### Inspringen

Je moet tabs gebruiken om je code te laten inspringen. In geen enkel geval gebruik je spaties als tabs.

Verticale afstanden (voor multi-line) wordt gedaan met spaties. Tabs zijn niet goed voor verticale uitlijning omdat verschillende mensen andere tabbreedtes hebben.

	<?php

	$text = 'this is a long text block that is wrapped. Normally, we aim for '
		  . 'wrapping at 80 chars. Vertical alignment is very important for '
		  . 'code readability. Remember that all indentation is done with tabs,'
		  . 'but vertical alignment should be completed with spaces, after '
		  . 'indenting with tabs.';

### String concatenatie

Plaats geen spaties rond de concatenatie operator:

	<?php

	// Correct:
	$str = 'one'.$var.'two';

	// Niet correct:
	$str = 'one'. $var .'two';
	$str = 'one' . $var . 'two';

### Enkelvoudige lijn Statements

Enkelvoudige lijn IF statements mogen enkel maar gebruikt worden wanneer het de normale executie stop zoals `return` of `continue`:

	<?php

	// Aanvaardbaar:
	if ($foo == $bar)
		return $foo;

	if ($foo == $bar)
		continue;

	if ($foo == $bar)
		break;

	if ($foo == $bar)
		throw new Exception('You screwed up!');

	// Niet aanvaardbaar:
	if ($baz == $bun)
		$baz = $bar + 2;

### Vergelijkingsoperatoren

Gebruik OR en AND in je statements:

	<?php

	// Correct:
	if (($foo AND $bar) OR ($b AND $c))

	// Niet correct:
	if (($foo && $bar) || ($b && $c))

Bij if/else blokken, gebruik `elseif`, niet `else if`:

	<?php

	// Correct:
	elseif ($bar)

	// Niet correct:
	else if($bar)

### Switch structuren

Iedere case, break en default moeten op een aparte lijn staan. Het blok binnenin een case of default moet met één tab ingesprongen worden.

	<?php

	switch ($var)
	{
		case 'bar':
		case 'foo':
			echo 'hello';
		break;
		case 1:
			echo 'one';
		break;
		default:
			echo 'bye';
		break;
	}

### Haakjes (Parentheses)

Er moet een spatie achter het statements naam staan, gevolgd door een haakje. Het ! (bang) karakter moet een spatie langs beide kanten hebben om de zichtbaarheid te maximaliseren. Je mag geen spatie hebben na het eerste haakje of voor de laatste haakje, enkel in het geval van een bang of type casting.

	<?php

	// Correct:
	if ($foo == $bar)
	if ( ! $foo)

	// Niet correct:
	if($foo == $bar)
	if(!$foo)
	if ((int) $foo)
	if ( $foo == $bar )
	if (! $foo)

### Ternaries

Alle ternaire operaties moeten volgens het standaard formaat. Gebruik enkel haakjes rond uitdrukkingen, niet rond enkel maar variabelen.

	$foo = ($bar == $foo) ? $foo : $bar;
	$foo = $bar ? $foo : $bar;

Alle vergelijkingen en bewerkingen moeten binnenin de haakjes gebeuren:

	$foo = ($bar > 5) ? ($bar + $foo) : strlen($bar);

Bij het scheiden van complexe ternaries (ternaries waarbij het eerste deel meer dan ~80 karakters bevat) in meerdere regels, moet je spaties gebruiken om operators op te lijnen, deze plaats je in het begin van de opeenvolgende lijnen:

	$foo = ($bar == $foo)
		 ? $foo
		 : $bar;

### Type Casting

Type casting wordt gedaan met spatie langs elke kant van de cast:

	// Correct:
	$foo = (string) $bar;
	if ( (string) $bar)

	// Niet correct:
	$foo = (string)$bar;

Indien mogelijk, gebruik dan in plaats van type casting ternaire operators:

	// Correct:
	$foo = (bool) $bar;

	// Niet correct:
	$foo = ($bar == TRUE) ? TRUE : FALSE;

Bij het casten van een integer of een boolean gebruik je het korte formaat:

	// Correct:
	$foo = (int) $bar;
	$foo = (bool) $bar;

	// Incorrect:
	$foo = (integer) $bar;
	$foo = (boolean) $bar;

### Constanten

Gebruik altijd hoofdletters voor constanten:

	// Correct:
	define('MY_CONSTANT', 'my_value');
	$a = TRUE;
	$b = NULL;

	// Niet correct:
	define('MyConstant', 'my_value');
	$a = True;
	$b = null;

Plaats constant vergelijkingen aan het einde van de tests:

	// Correct:
	if ($foo !== FALSE)

	// Niet correct:
	if (FALSE !== $foo)

Dit is een enigszins een controversiële keuze, dus is een uitleg op zijn plaats. Als we het vorige voorbeeld in gewoon taal schrijven, zou het goede voorbeeld als volgt te lezen zijn:

	if variable $foo is not exactly FALSE

En het foute voorbeeld zou als volgt te lezen zijn:

	if FALSE is not exactly variable $foo

En aangezien we van links naar rechts lezen, is het logischer om de constante als laatste te plaatsen.

### Commentaren

#### Commentaren op één lijn

Gebruik //, best boven de lijn met je code waar je de commentaar voor wilt schrijven. Laat een spatie tussen en start met een hoofdletter. Gebruik nooit #

	// Correct

	//Niet correct
	// niet correct
	# Niet correct

### Reguliere expressies

Bij het coderen van reguliere expressies gebruik je beter PCRE in plaats van POSIX. PCRE zou krachtiger en sneller zijn.

	// Correct:
	if (preg_match('/abc/i'), $str)

	// Incorrect:
	if (eregi('abc', $str))

Gebruik enkele aanhalingstekens rond uw reguliere expressies in plaats van dubbele aanhalingstekens. Enkele aanhalingstekens worden gemakkelijker door hun eenvoud in gebruik. In tegenstelling tot de dubbele aanhalingstekens ondersteunen ze niet variabele interpolatie, noch geïntegreerde backslash sequenties zoals \n of \t, enz.

	// Correct:
	preg_match('/abc/', $str);

	// Incorrect:
	preg_match("/abc/", $str);

Bij het uitvoeren van een reguliere expressie zoeken en vervangen, gebruik dan de $n notatie voor terugverwijzingen. Dit verdient de voorkeur boven \\n.

	// Correct:
	preg_replace('/(\d+) dollar/', '$1 euro', $str);

	// Incorrect:
	preg_replace('/(\d+) dollar/', '\\1 euro', $str);

Tot slot, let wel dat het $-teken voor de eindpositie van de lijn aan te geven toelaat om een newline-karakter als volgend karakter te gebruiken. Gebruik de D modifier om dit te verhelpen indien nodig. [Meer informatie](http://blog.php-security.org/archives/76-Holes-in-most-preg_match-filters.html).

	$str = "email@example.com\n";

	preg_match('/^.+@.+$/', $str);  // TRUE
	preg_match('/^.+@.+$/D', $str); // FALSE
