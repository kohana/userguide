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
