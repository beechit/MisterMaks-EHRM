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

 Notification.showInfo('Info text for user');

Success
-------
Can be used to display success information about action of user or system.

Usage::

 Notification.showSuccess('Data is updated');

Error
-----
Should be displayed whe action of user/system was failed.

Usage::

 Notification.showError('Saving a form failed');

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
			Notification.showSuccess('Record was deleted');
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

 Notification.showDialog('Are you sure you want to delete this record?', actions);


