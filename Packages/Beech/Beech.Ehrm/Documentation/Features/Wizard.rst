==========
The Wizard
==========

... knows magic

General Wizard considerations
=============================

The Wizard implementation can be used to generate alternative inputforms for endusers, in order to
present them a more userfriendly experience while filling out data. To achieve this, the wizard
implements it's own set of validation rules, allowing us to bypass the validation set on the model
itself. The Wizard relies on the TYPO3.FormBuilder and TYPO3.Form libraries.

Use the TYPO3.FormBuilder library to create wizards
---------------------------------------------------

The TYPO3.FormBuilder provides a convenient and flexible GUI to create forms. The library itself seems
to be very extendable, so we should easily be able to fit in new functionality if needed.

The FormBuilder uses Finishers to determine what should be done with a form once it's posted. To
implement the wizard, a new DatabaseFinisher was added. The finisher maps the form's input to a model,
in order to persist it's data. It needs two arguments which can be set withing the FormBuilder:

* Package Key
* Model Name

The ``package key`` relates to the package in which the form's corresponding model can be found. Of course
the ``model name`` relates to the model itself.

Validation
----------

The Wizard presents a problem regarding validation of a model. We want our normal (kickstarted) routes to
validate the model, but using the Wizard we want to skip most of them.

Consider having a Wizardform just to add name-details. The model it works on could be ``Person``, which
probably has some required address-details as well. The trick is to only validate the required field when
called using a controller. FLOW3 has neat annotation for this, the validationGroup: ::

	class Person {

		/**
		 * @FLOW3\Validate(type="NotEmpty", validationGroups={"Controller"})
		 */
		protected $street;

	}

So, only add the NotEmpty validation on properties that should **never** be empty. Otherwise add the
validationGroups={"Controller"} part to the annotation.

Other validation, like MinLength for instance, should always be used on the model without adding the
validationGroups. These rules apply in both regular and wizard mode.

Overriding wizards for customization
------------------------------------

Forms created by the FormBuilder will be stored as a .yaml file in /Data/Forms. If we want to remove fields
from the form or add custom functionality to the form, we can simply modifie a copy the .yaml file and place
it in /Beech.Ehrm/Resources/Private/Wizards. The wizard will always look for a configured form there first.

Using this setup, we could (automatically?) generate complete forms for all existing models. Then we could
store parts of these forms as Wizards in the Resources/Private/Wizards folder.

Another strategy could be to tweak the wizardforms to the specific needs or wishes of a customer.

Step by step
------------

To sum things up, have a look at this step-by-step miniguide to create a wizard:

* Prerequisit: make sure you have a domain model to test with
* Go to /typo3.formbuilder and create a new form, based on your intended domain model. Naming of the identifiers
  of fields is important! They should relate to properties of the model.
* Add a Database Persistence Finisher to the form with the correct Package Key (i.e.: Beech\Party) and Model
  Name (i.e.: Company)
* Save your form. A yourForm.yaml file will be stored in /Data/Forms
* Navigate to /beech.ehrm/wizard/show?formPersistenceIdentifier=yourForm (you can add &presetName=bootstrap to
  add style) to open the created Wizardform
* If you feel the need to customize the form: open the created yourForm.yaml file from /Data/Forms, edit it as
  you see fit and save the form in /Beech.Ehrm/Resources/Private/Wizards/yourForm.yaml
* Again: Navigate to /beech.ehrm/wizard/show?formPersistenceIdentifier=yourForm to see changes
* If the validationrules on your model are correct, you should be able to save your Wizard. Check your database
  to find the added record.
