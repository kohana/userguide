# Installation

1. Download the latest **stable** release from the [Kohana website](http://kohanaphp.com/)
2. Unzip the downloaded package to create a `kohana` directory
3. Upload the contents of this folder to your webserver
4. Open `application/bootstrap.php` and make the following changes:
	- Set the default [timezone](http://php.net/timezones) for your application
	- Set the `base_url` in the [Kohana::init] call to reflect the location of the kohana folder on your server
6. Make sure the `application/cache` and `application/logs` directories are world writable with `chmod application/{cache,logs} 0777`
7. Test your installation by opening the URL you set as the `base_url` in your favorite browser

[!!] Depending on your platform, the installation's subdirs may have lost their permissions thanks to zip extraction. Chmod them all to 755 by running `find . -type d -exec chmod 0755 {} \;` from the root of your Kohana installation.

You should see the installation page. If it reports any errors, you will need to correct them before contining.

![Install Page](img/install.png "Example of install page")

Once your install page reports that your environment is set up correctly you need to either rename or delete `install.php` in the root directory. You should then see the Kohana welcome page. (?? Currently just 'Hello World!' in KO3 ??)

