#!/bin/bash

user="vagrant"
group="vagrant"

# Preseed MySQL package installation
mkdir -p /var/cache/local/preseeding
chmod 0755 /var/cache/local/preseeding

echo 'mysql-server-5.0        mysql-server/root_password_again        select dbpassword
mysql-server-5.0        mysql-server/root_password              select dbpassword
mysql-server-5.0        mysql-server-5.0/really_downgrade       boolean false
mysql-server-5.0        mysql-server-5.0/need_sarge_compat      boolean false
mysql-server-5.0        mysql-server-5.0/start_on_boot  boolean true
mysql-server-5.0        mysql-server/error_setting_password     boolean false
mysql-server-5.0        mysql-server-5.0/nis_warning    note
mysql-server-5.0        mysql-server-5.0/postrm_remove_databases        boolean false
mysql-server-5.0        mysql-server/password_mismatch  boolean false
mysql-server-5.0        mysql-server-5.0/need_sarge_compat_done boolean true
' > /var/cache/local/preseeding/mysql-server.seed
chmod 0600 /var/cache/local/preseeding/mysql-server.seed

debconf-set-selections /var/cache/local/preseeding/mysql-server.seed

# Install packages
apt-get update
apt-get install -y apache2 php5 libapache2-mod-php5 curl php5-gd php5-curl php5-mysql php5-ldap mysql-server php5-xdebug git-core python-setuptools tree mc php-pear

# Install Sphinx
easy_install -U Sphinx

# Install PEAR package
pear channel-discover pear.phing.info
pear install phing/phing

pear channel-discover pear.phpunit.de
pear channel-discover components.ez.no
pear install phpunit/PHPUnit
pear install phpunit/phpcpd

pear channel-discover pear.phpmd.org
pear channel-discover pear.pdepend.org
pear install -a phpmd/PHP_PMD-alpha

pear channel-discover pear.pdepend.org
pear install pdepend/PHP_Depend-beta

pear install PHP_UML
pear install PhpDocumentor
pear install XML_Util

# Configure Apache
sed -i "s/APACHE_RUN_USER=www-data/APACHE_RUN_USER=$user/g" /etc/apache2/envvars
sed -i "s/APACHE_RUN_GROUP=www-data/APACHE_RUN_GROUP=$group/g" /etc/apache2/envvars

echo 'NameVirtualHost *:80
<VirtualHost *:80>

	DocumentRoot "/var/www/Web/"
	ServerName ehrm
	ServerAlias ehrm.local

 	ErrorLog "/var/log/apache2/flow3-error_log"
 	CustomLog "/var/log/apache2/flow3-access_log" common
	LogLevel warn

	<Directory /var/www/>
		Options -Indexes FollowSymLinks MultiViews

		AllowOverride All
		Order deny,allow
		SetEnv FLOW3_CONTEXT Development

		php_admin_value post_max_size 200M
		php_admin_value upload_max_filesize 200M
		php_admin_flag display_errors 1
		php_admin_value memory_limit 128M
	</Directory>
</VirtualHost>' > /etc/apache2/sites-available/web

a2enmod rewrite
a2ensite web

service apache2 restart

exit 0