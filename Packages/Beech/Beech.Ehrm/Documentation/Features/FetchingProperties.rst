===================
Fetching Properties
===================


General
-------

To make values within collections of items accessible and possible to fetch only one of a specific type possible
a Override for magic __get was created to fetch single properties out of of a collections filtered on type and primary.


Example
-------

A company has many phoneNumbers, But there is one primary business phoneNumber of this company
we only want to render this phone number in some view.


syntax
------
to fetch this phoneNumber you can get it in fluid by calling the value this way::

	{company.phoneNumber_businessPhone.phoneNumber}


	*company* is the entity we want to have this phoneNumber from.
	*PhoneNumber* is the model that contains the property that we want
	*_bushinessPhone* is the stored key of the type of phoneNumber.
	*phoneNumber* is the property we want to render.


