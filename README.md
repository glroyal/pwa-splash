#pwa-splash 
>A quick and dirty PHP script for generating a complete set of splash screens for your PWA. 
> 
> Gary Royal

## Features
 
 * Generates portrait-oriented splash screens for Retina devices from iPod Touch 5 to iPad Pro 12.9
 * Generates optional landscape-oriented splash screens
 * Generates HTML meta tags for all spash screens

## Requirements

* PHP 8.1
* GD (php8.1-gd)
* Your pwa logo (png only, 512px or larger, solid, no transparency

## How to Use

```
	cd /usr/local/src 
	git clone https://github.com/glroyal/pwa-splash
	cd pwa-splash
```
The simplest way to check if the GD image library is available is to attempt to install it. 
```
	sudo apt install php8.1-gd
```
If GD is already installed, you'll see a message like this:
```
php8.1-gd is already the newest version (8.1.11-1+ubuntu22.04.1+deb.sury.org+2).
0 upgraded, 0 newly installed, 0 to remove and 199 not upgraded.
```
Otherwise, GD will be installed and you'll be ready to go.
```
	php splash.php
```
By default, a set of sample screens and a list of meta tags will be generated in the current directory in a folder called `splash_screens`. 

Alter the `splash.php` script to suit your own needs.  


