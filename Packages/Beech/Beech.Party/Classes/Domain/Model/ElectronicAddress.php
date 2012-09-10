<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 03-08-12 10:04
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * An ElectronicAddress
 *
 * @FLOW3\Entity
 */
class ElectronicAddress extends \TYPO3\Party\Domain\Model\ElectronicAddress {

	const TYPE_PHONE = 'Phone';
	const TYPE_MOBILE_PHONE = 'MobilePhone';

	/**
	 * @var string
	 * @FLOW3\Validate(type="Alphanumeric")
	 * @FLOW3\Validate(type="StringLength", options={ "minimum"=1, "maximum"=30 })
	 * @ORM\Column(length=30)
	 */
	protected $type;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var string
	 */
	protected $code;

	/**
	 * Construct the object
	 */
	public function __construct() {
		parent::__construct();
		$this->setCode('');
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $code
	 */
	public function setCode($code) {
		$this->code = $code;
	}

	/**
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

}

?>