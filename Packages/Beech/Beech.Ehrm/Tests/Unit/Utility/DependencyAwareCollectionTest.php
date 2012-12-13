<?php
namespace Beech\Ehrm\Tests\Unit\Utility;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 12/13/12 5:54 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use \Beech\Ehrm\Utility\DependencyAwareCollection;

class DependencyAwareCollectionTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function itemsCanBeAddedToTheCollection() {
		$collection = new DependencyAwareCollection();
		$collection->add('foo');
		$collection->add('bar');

		$this->assertEquals(2, count($collection->getItems()));
	}

	/**
	 * @test
	 */
	public function itemsWithTheSameIdentifierOverrideAlreadySetItemsWithTheSameIdentifier() {
		$collection = new DependencyAwareCollection();
		$collection->add('foo');
		$collection->add('foo');

		$this->assertEquals(1, count($collection->getItems()));
	}

	/**
	 * @test
	 */
	public function orderOfItemsWithoutAnyDependenciesIsPreserved() {
		$collection = new DependencyAwareCollection();
		$collection->add('foo');
		$collection->add('bar');

		$items = $collection->getItems();
		$this->assertEquals('foo', $items[0]['identifier']);
		$this->assertEquals('bar', $items[1]['identifier']);
	}

	/**
	 * @test
	 */
	public function itemsWithDependenciesAreMovedToTheBeginning() {
		$collection = new DependencyAwareCollection();
		$collection->add('foo');
		$collection->add('bar', array('baz'));

		$items = $collection->getItems();
		$this->assertEquals('bar', $items[0]['identifier']);
		$this->assertEquals('foo', $items[1]['identifier']);
	}

	/**
	 * @test
	 */
	public function aSimpleSortWithTwoItemsIsDoneCorrectly() {
		$collection = new DependencyAwareCollection();
		$collection->add('bar', array('baz'));
		$collection->add('baz', array('foo'));

		$items = $collection->getItems();
		$this->assertEquals('baz', $items[0]['identifier']);
		$this->assertEquals('bar', $items[1]['identifier']);
	}

	/**
	 * @test
	 */
	public function aSortWithOneItemDependingOnTwoOthersIsHandledCorrectly() {
		$collection = new DependencyAwareCollection();
		$collection->add('bar', array('baz', 'foo'));
		$collection->add('baz', array('null'));
		$collection->add('foo', array('null'));

		$items = $collection->getItems();
		$this->assertEquals('bar', $items[2]['identifier']);
	}

	/**
	 * @test
	 */
	public function aSortWithThreeItemsDependingOnEachOtherIsHandledCorrectly() {
		$collection = new DependencyAwareCollection();
		$collection->add('foo', array('jquery', 'bootstrap'));
		$collection->add('bootstrap', array('jquery'));
		$collection->add('jquery');

		$items = $collection->getItems();
		$this->assertEquals('jquery', $items[0]['identifier']);
		$this->assertEquals('bootstrap', $items[1]['identifier']);
		$this->assertEquals('foo', $items[2]['identifier']);
	}

	/**
	 * @test
	 */
	public function moreComplexSetIsOrderedCorrectly() {
		$this->markTestSkipped('TODO: Fully implement complex dependency handling');
		$items = array(
			'fooLibrary' => array('jquery-ui-twitter-bootstrap-thing'),
			'emberjs-jquery-ui-mixin' => array('jquery-ui-plugin', 'emberjs', 'bootstrap'),
			'jquery-ui-twitter-bootstrap-thing' => array('jquery', 'emberjs-jquery-ui-mixin'),
			'handlebars' => array('jquery'),
			'emberjs' => array('handlebars', 'jquery'),
			'bootstrap' => array('emberjs'),
			'jquery-ui' => array('jquery', 'bootstrap'),
			'jquery-ui-plugin' => array('jquery-ui', 'jquery', 'bootstrap'),
			'jquery' => array()
		);

		$collection = new DependencyAwareCollection();
		foreach ($items as $identifier => $deps) {
			$collection->add($identifier, $deps);
		}

		$correct = array(
			'jquery',
			'handlebars',
			'emberjs',
			'bootstrap',
			'jquery-ui',
			'jquery-ui-plugin',
			'emberjs-jquery-ui-mixin',
			'jquery-ui-twitter-bootstrap-thing',
			'fooLibrary'
		);

		$items = $collection->getItems();
		$match = array();
		foreach ($items as $item) {
			$match[] = $item['identifier'];
		}

		$this->assertEquals($correct, $match);
	}
}

?>