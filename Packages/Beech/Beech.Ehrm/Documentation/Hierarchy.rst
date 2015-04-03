Hierarchy (JobPosition)
=======================

Goal
====

This part sets the hierarchy in an organisation.

Hierarchy can be the order how departments interact.
or the tree of how persons are ordered in an organisation.

We don't want to order persons directly but use the jobdescriptions as a layer between persons and the hierarchical position
in an organisation.

in this way if a person leaves the organisation the jobposition with rights and roles can be taken by a new employee.

Hierarchy must give an overview how a company or department is organised.
Als in this hierarchy structure the Tasks, permissions and responsibilities are arranged.
this is also the base for setting permissions in attached security groups in for every module in the system.
Hierarchy sets the roles, rights and permissions that a users has based on his function and attached roles.

In Hierarchy we intend to look up to find the manager from an employee and down for manager with employees,
or department head with staff.

Also the excalationmodel in workflow we want to follow the configured hierarchy.

Domain Model
============

see the hierarchy model /Packages/Beech/Beech.Ehrm/Configuration/Models.JobPosition.yaml


Overview
========

.. image:: ../../Resources/Images/hierarchy.jpg
	:scale: 600px
	:align: center

Hierarchy
=========

The Hierarchy has the following properties:

* Connection with jobdescriptions (TODO:: make the jobsdesciptions unique for the employee
* Connection with Company and CompanyDepartment
* Connection with Group for setting roles.
* Connection with accounts
* creationDate

About
=====
Probably in a visual view the organization structure is shown, this organization structure is build up out of functions
and functions are occupied by persons.
Roles an rights are attached to the package of a function in the hierarchy and the person occupying the function.

