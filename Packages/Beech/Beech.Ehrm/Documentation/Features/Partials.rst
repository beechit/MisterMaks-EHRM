Render partials from other packages
------------------------------------

[FEATURE] Enable render partials from other packages

It is possible to reuse partials from
other packages by adding packageKey prefix to partial argument
Example:
<f:render partial="Beech.Document:Form/New" arguments="{_all}"/>
To use this feature, package must use TemplateView from Beech.Ehrm package
It can be done by adding to Views.yaml, line:
  viewObjectName: 'Beech\Ehrm\View\TemplateView'