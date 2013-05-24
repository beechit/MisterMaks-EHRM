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

StatusModel
-----------

A status model was introduced, this is the general place where to check and store states.
The states model is to be used to register states of the entities in mister maks.
The workflow package needs to be refactored to work with the status model.

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


Process Examples
================

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

New employee
------------

New contract workflow I
-----------------------

preconditions:

a new contract was created:
this is the first contract for this person.
In this contract there is a probation

Workflow

Create a task for the 'manager' of this person
(first we should use contract creator, because hierarchy is not implemented yet).

this task can be plan a meeting ok give close on this task (with remark) this should be done before the expiration of the probation.
this notification should appear 2 weeks before the probation ends with normal priority.
if this meeting is not planned before 4 days before ending of probation.
the priority of this task should be raised to highest level.

if 1 day before probation ends this meeting is not planned a new task will be created for the manager of the manager
saying, no interview planned with employee before ending of probation. (this is a closable task for this manager).

New contract workflow II
------------------------

Preconditions:
a new contract was created:
this is the first contract for this person.
there is no valid passport or id card specified and uploaded in the system.


create task for manager of this employee.
(first we should use contract creator, because hierarchy is not implemented yet).
to upload a valid id or passport in the system.
this task should have priority high and has a open window of 2 days and is not closable
after this the priority gets raised.

After not fulfilling this task for 2 more days then a new task is created for the manager of the manager.
saying that person got hired without valid passport!!!


New contract workflow III
------------------------

Preconditions:
a new contract was created with status draft.

after 2 days a task is created for the manager of this person.
(first we should use contract creator, because hierarchy is not implemented yet).
this task is not closable. and should say something Set status of (persons) contract.

when status is set to accepted workflow IV starts
when status is set to rejected or other states the task will be automatically closed.


New contract workflow IV
------------------------

Preconditions:
a new contract was created:
this is the first contract for this person.
there is no signed version of the contract uploaded to the system.
status of the contract is accepted.

create task for manager of this employee.
(first we should use contract creator, because hierarchy is not implemented yet).
to upload the signed version in the system en s
this task should have priority high and has a open window of 2 days and is not closable
after this the priority gets raised.

After not fulfilling this task for 2 more days then a new task is created for the manager of the manager.
saying that person got hired without valid passport!!!


New contract workflow IV
------------------------

Preconditions:
a new contract was created
No or invalid work permit was added and uploaded

start date of contract is a week before start or >
the nationality of the person is one that needs a work permit. (in nationality resource work permit=TRUE)
meta info of work permit matches contract.


create a non closable task to upload the work permit in the system. (high priority)

if start date has passed already then highest priority and task for manager of manager.....



future features
---------------

assign task to other people in organisation todo a workflow (delegate)

create workflow on entities.
like if a new person is registered in the system en create a task (with workflow for a person to create the contract for this
new person.
