=========
Modal box
=========

General info
============

Modal box plugin allows to load and display content in dialog/modal box.
This action can be performed without reload of page, using ajax.

How to use it
=============
To use this feature, there must be condition fulfilled:
 * container for modal box must be set

   <div class="modal fade" id="entityModal"></div>
 * default 'loading' element must be set

   <div class="loading hide">Default loading content here</div>
 * element with arguments: href, data-toggle and data-target, where:

href
 This argument define URL of source which will be loaded
data-toggle="modal"
 This argument makes that html element becomes modal loader, after click.
data-target="entityModal"
 This argument points to id of modal box container

Default containers example::

	<div class="modal fade" id="entityModal"></div>
	<div class="loading hide"><f:render partial="ModalContent" arguments="{title: 'Loading...', content: ''}"/></div>


Suggestion/TODO
===============
Some parts could be extended in the future:
 * make usage more simple, maybe by moving container of modal and container with default content to layout template
