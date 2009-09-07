# Installation

## Basic Installation

1. Download the latest stable release from [kohanaphp.com/download](http://kohanaphp.com/download).

2. Unzip the downloaded package. You should end up with a `kohana-v[version]` directory.

3. Upload the contents of this folder to your webserver.

4. Open `application/bootstrap.php` and make the following changes:

	4.1. Set the default timezone for your application. ([see the manual for more info.](http://php.net/timezones))

	4.2. Set the `base_url` in the `Kohana::init()` call to reflect the location of the kohana folder on your server.

5. Depending on your platform, the installation's subdirs may have lost their permissions thanks to zip extraction. Chmod them all to 755 by running `find . -type d -exec chmod 755 {} \;` from the root of your Kohana installation.

6. Make sure the `application/cache` and `application/logs` directories are writable by the apache user (or the user your PHP scripts run under. Typically this means chmodding them to 666.

7. Test your installation by opening the URL you set as the `base_url` in your favorite browser.

You should see the **install** page. If it reports any errors, you will need to correct them before contining.

Once your install page reports that your environment is set up correctly you need to either rename or delete `install.php` in the root directory. You should then see the Kohana welcome page. (?? Currently just 'Hello World!' in KO3 ??)

