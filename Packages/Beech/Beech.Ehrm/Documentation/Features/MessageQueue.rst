=============
Message Queue
=============

General info
============

Message Queue plugin is extension of Notification plugin.
It allows to join notification into queues, and display them one by one or all together.

Requirements
============

This plugin require Notification plugin to proper work.
Notification plugin should be initialize before MessageQueue plugin.


How to use it
=============
To use this plugin, first it must be initialized:
MessageQueue.initialize();

Adding to queue
---------------
To add notification to queue three params must be defined: id, text, type

MessageQueue.add(1, 'Hello', Notification.INFO);
MessageQueue.add(33, 'Watch out!', Notification.HIGH);

Display notification
--------------------

To display notification from queue we have two possibilities:

Display first notification from a queue
MessageQueue.showMessage();

Display by id
MessageQueue.showMessage(33);

Count notifications
-------------------

To count how many notification is in the queue:
MessageQueue.count();
