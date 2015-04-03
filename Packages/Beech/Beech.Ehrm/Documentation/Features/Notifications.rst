=============
Notifications
=============

General info about plugin
=========================

Notifications is javascript/jquery plugin, which supports displaying info boxes for system users.

Implementation is based on:
 * jquery.midgardNotification plugin: http://createjs.org/guide/#notifications
 * Twitter.bootstrap: http://twitter.github.com/bootstrap/components.html#alerts


Init and usage
==============
Initialization of Notification plugin is realized in JavaScript/Init.js
To init and allow usage of notifications in project, inside other modules use requireJS


Usage::
define(['notification'], function(Notification) {
	Notification.initialize();
	Notification.showInfo('Notification message');
});


Types of notifications
======================
After initialization, Notifications enable to display several types of notifications:

Info
----
Can be used to display simple information for user.

Usage::

 App.Service.Notification.showInfo('Info text for user');

Success
-------
Can be used to display success information about action of user or system.

Usage::

 App.Service.Notification.showSuccess('Data is updated');

Warning
-----
Can be displayed to warn the user.

Usage::

 App.Service.Notification.showWarning('Your account is expiring!');

Error
-----
Should be displayed whe action of user/system was failed.

Usage::

 App.Service.Notification.showError('Saving a form failed');

Dialog
------
Dialog notification allow possibility to display more interactive, chained notifications.

It supports setting up second argument with actions.

Its possible to set multiple buttons, separately for each action and also combine them with callback function.
Inside callback function there can be another Notifications executed.

Usage::

 var actions =
 [
	{
		name: 'yes', label: 'Yes',
		cb: function(e, notification) {
			App.Service.Notification.showSuccess('Record was deleted');
			notification.close();
		}
	},
	{
		name: 'no', label: 'No',
		cb: function(e, notification) {
			notification.close();
		}
	}
 ];

 App.Service.Notification.showDialog('Are you sure you want to delete this record?', 'Confirmation required!', actions);

Timeout settings
================
By default, timeout is set to 5 seconds.

The are some ways to manipulate time.

Global timeout
--------------

Function ``setTimeout`` allows to set/change default time of timeout::

 App.Service.Notification.setTimeout(2000); //2000 = 2 seconds

Timeout for one notification
----------------------------

Its possible to set timeout only for selected notification::

 App.Service.Notification.showInfo('I go home in one second', 'Info', 1000);

Sticky notification
-------------------

To make notification displayed until user reaction use ``0`` value for timeout::

 App.Service.Notification.showError('You made a BIG mistake', false, 0);

