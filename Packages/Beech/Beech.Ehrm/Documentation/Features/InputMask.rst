==========
Input mask
==========

General info
============

Input mask allow to force user to input correct format of data

How to use it
=============

There some predefined input masks. It can be apply by adding class name to input field

Example to make input data uppercase:

<f:form.textfield property="initials" id="initials" class="uppercase"/>


Available input masks
=====================
 * phone
	Input mask for phone number
 * postal
	Input mask for postal code
 * bsn
	Input mask for BSN number
 * uppercase
	Input mask makes all letters uppercase
 * capitalize
 	Input mask which makes first letter uppercase


How to add more input masks
===========================

All rules/conditions for input masks are stored in file:
Packages/Beech/Beech.Ehrm/Resources/Public/JavaScript/Component/UserInterface/InputMask.js


