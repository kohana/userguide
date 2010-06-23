# Installatie

1. Download de laatste **stabiele** release van de [Kohana website](http://kohanaframework.org/)
2. Unzip het gedownloade pakket om de `kohana` folder aan te maken
3. Upload de inhoud van deze folder naar je webserver
4. Open `application/bootstrap.php` en maak de volgende aanpassingen:
	- Stel de standaard [timezone](http://php.net/timezones) in voor je applicatie
	- Stel de `base_url` in de [Kohana::init] methode in om te verwijzen naar de locatie van de kohana folder op je server
6. Zorg ervoor dat de `application/cache` en `application/logs` folders schrijfrechten hebben voor de web server
7. Test je installatie door de URL te openen in je favoriete browser dat je hebt ingesteld als `base_url`

[!!] Afhankelijk van je platform is het mogelijk dat de installatie subfolders hun rechten verloren hebben tijdens de zip extractie. Chmod ze allemaal met 755 door het commando `find . -type d -exec chmod 0755 {} \;` uit te voeren in de root van je Kohana installatie.

Je zou de installatie pagina moeten zien. Als het errors toont, zal je ze moeten aanpassen vooraleer je verder kunt gaan.

![Install Page](img/install.png "Voorbeeld van de installatie pagina")

Eens je installatie pagina zegt dat je omgeving goed is ingesteld dan moet je de `install.php` pagina hernoemen of verwijderen in de root folder. Je zou nu de de Kohana welcome pagina moeten zien:

![Welcome Page](img/welcome.png "Voorbeeld van welcome pagina")
