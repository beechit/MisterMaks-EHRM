=====================
Patch for TYPO3 Party
=====================


We can't persist persons without a patch to the TYPO3 party package.

to fix this the person model in this package should be patched.

in /Packages/Framework/TYPO3.Party/Classes/TYPO3/Party/Domain/Model/Person.php
line 26::

	 * @ORM\OneToOne

Should be changed into::

	* @ORM\OneToOne(cascade={"persist"})


Todo: create a workaround so that we don't have to patch this package.