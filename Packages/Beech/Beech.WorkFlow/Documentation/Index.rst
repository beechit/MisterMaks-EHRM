==============
Beech Workflow
==============

The workflow modules main task is chaining actions, and execute the next
action if certain conditions are met. The output of an action could be
a (list of) new actions.

Terms
=====

Action
------

Something to do, this could be creating a new todo, send a mail or put a
new job into the jobqueue.

Pre-condition
-------------

Every action can have multiple pre-conditions. Only if all pre-conditions
are met the action will be executed.

Validations
-----------

Every action can have multiple validations. If all of those validations are
met, the actions is considered done. Depending on the validation it can also
have output defined. This way a new step in the process could already start
even if not all validations are met yet.

Listeners
---------

A specific action 'listening' to status changes of the action (like passing
the expiration date).

Output
------

The output of an action can be a variety of things. For this an interface has
to be created to ensure extensibility and manageability. Think about output
in terms of a new ``Action``, an ``EmailMessage`` or whatever output needed.

Target
------

A pointer to the entity the process is all about. This should always be one,
and just one, entity. If multiple targets are involved we should try to split
the process, and try hard to prevent the complexity of multiple targets.

Status
------

The current status of the action. Could be:

* New
	The action is created, not all pre-conditions are met yet
* Started
	All pre-conditions are met and the process is started
* Finished
	All validations are met and the action is finished
* Expired
	The expiration date has passed
* Terminated
	The process is killed by the system / a user

Owner
-----

The current 'owner' of the action, this should be the person responsible for
the process (does not have to be the current handler of a todo for example, this
would be output of the action. But it should be the person to escalate to).

Creator
-------

The initial creator of the action.

Parent
------

An optional Action which 'created' this action.


Process Example
===============

New employee
------------

When a new employee is added to the system the user will probably use a wizard.
When the wizard is completed a not yet complete person will be saved to the database.
Of course we will need a lot more information, as the wizard for example only added
the name of the person. So now we could have some new actions to start:

* Fill in the full address
	* If this is done flowers should be send to the new employee
* Create a todo for some employee to make sure all financial data is in the system

and so on...

TODO: Extend example