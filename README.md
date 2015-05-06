# mistermaks
Mr maks is proces based ehrm system based on TYPO3 flow.
The customer behind this project canceled the project
so the work is not finished. (85%)

Features are
------------
- Dynamic models
- Dual database setup (mysql and couchdb)
- One page application on emberjs. 
- Ajax loading of templates.
- Dynamic wizards.
- Dynamic configurable Process engine.
- Socketserver based notifications.
- ember widgets for charts and notifications.
- and more...
 

this project is not ready but it runs and is rich of code examples.

Requirements
============
- Apache webserver
- Mysql
- PHP 5.4 ->
- Couchdb

setup instructions.
===================
-  Clone this repository
-  cd /mistermaks
-  curl -sS https://getcomposer.org/installer | php
-  php composer.phar install
-  php gerrit_update.php
-  ./initialise-mistermaks.sh
-  create a vhost and its up and running.

