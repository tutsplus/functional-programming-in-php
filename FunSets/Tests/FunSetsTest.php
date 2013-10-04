<?php

require_once __DIR__ . '/../FunSets.php';

class FunSetsTest extends PHPUnit_Framework_TestCase {

	private $funSets;

	protected function setUp() {
		$this->funSets = new FunSets();
	}

	function testContainsIsImplemented() {
		// We caracterize a set by it's contains function. It is the basic function of a set.

		$set = function ($element) {
					return true;
				};
		$this->assertTrue($this->funSets->contains($set, 100));
	}

	function testSingletonSetContainsSingleElement() {
		// A singleton set is characterize by a function which passed to contains will return true for the single element
		// passed as it's parameter. In other words, a singleton is a set with a single element.

		$singleton = $this->funSets->singletonSet(1);
		$this->assertTrue($this->funSets->contains($singleton, 1));
	}

	function testUnionContainsAllElements() {
		// A union is characterized by a function which gets 2 sets as parameters and contains all the provided sets
		// We can only create singletons at this point, so we create 2 singletons and unite them
		$s1 = $this->funSets->singletonSet(1);
		$s2 = $this->funSets->singletonSet(2);
		$union = $this->funSets->union($s1, $s2);

		// Now, check that both 1 and 2 are part of the union
		$this->assertTrue($this->funSets->contains($union, 1));
		$this->assertTrue($this->funSets->contains($union, 2));
		// ... and that it does not contain 3
		$this->assertFalse($this->funSets->contains($union, 3));
	}

	function testIntersectionContainsOnlyCommonElements() {
		$u12 = $this->createUnionWithElements(1, 2);
		$u23 = $this->createUnionWithElements(2, 3);
		$intersection = $this->funSets->intersect($u12, $u23);

		// Verify intersection has common elements
		$this->assertTrue($this->funSets->contains($intersection, 2));
		// Check intersection does not have unique elements
		$this->assertFalse($this->funSets->contains($intersection, 1));
		$this->assertFalse($this->funSets->contains($intersection, 3));
	}

	function testDiffContainsOnlyUniqueElementsFromTheFirstArray() {
		$u12 = $this->createUnionWithElements(1, 2);
		$u23 = $this->createUnionWithElements(2, 3);
		$difference = $this->funSets->diff($u12, $u23);

		// Verify difference has the unique element from the first set
		$this->assertTrue($this->funSets->contains($difference, 1));
		// Check difference does not have the common element
		$this->assertFalse($this->funSets->contains($difference, 2));
		// Check difference does not have the element form the second set
		$this->assertFalse($this->funSets->contains($difference, 3));
	}

	function testFilterContainsOnlyElementsThatMatchConditionFunction() {
		$u123 = $this->createUnionWith123();

		// Filtering rule, find elements greater than 1 (meaning 2 and 3)
		$condition = function($elem) { return $elem > 1; };

		// Filtered set
		$filteredSet = $this->funSets->filter($u123, $condition);

		// Verify filtered set does not contain 1
		$this->assertFalse($this->funSets->contains($filteredSet, 1), "Should not contain 1");
		// Check it contains 2 and 3
		$this->assertTrue($this->funSets->contains($filteredSet, 2), "Should contain 2");
		$this->assertTrue($this->funSets->contains($filteredSet, 3), "Should contain 3");
	}

	function testForAllCorrectlyTellsIfAllElementsSatisfyCondition() {
		$u123 = $this->createUnionWith123();

		$higherThanZero = function($elem) { return $elem > 0; };
		$higherThanOne = function($elem) { return $elem > 1; };
		$higherThanTwo = function($elem) { return $elem > 2; };

		$this->assertTrue($this->funSets->forall($u123, $higherThanZero));
		$this->assertFalse($this->funSets->forall($u123, $higherThanOne));
		$this->assertFalse($this->funSets->forall($u123, $higherThanTwo));
	}

	function testExistsCorrectlyTellsIfAnElementsSatisfiesCondition() {
		$u123 = $this->createUnionWith123();

		$higherThanZero = function($elem) { return $elem > 0; };
		$higherThanOne = function($elem) { return $elem > 1; };
		$higherThanTwo = function($elem) { return $elem > 2; };
		$higherThanThree = function($elem) { return $elem > 3; };

		$this->assertTrue($this->funSets->exists($u123, $higherThanZero));
		$this->assertTrue($this->funSets->exists($u123, $higherThanOne));
		$this->assertTrue($this->funSets->exists($u123, $higherThanTwo));
		$this->assertFalse($this->funSets->exists($u123, $higherThanThree));
	}

	function testMapAppliesFunctionToAllElements() {
		$u12 = $this->createUnionWithElements(1, 2);

		$double = function($elem) { return $elem * 2; };
		$mapped = $this->funSets->map($u12, $double);

		$this->assertTrue($this->funSets->contains($mapped, 2));
		$this->assertTrue($this->funSets->contains($mapped, 4));
	}

	private function createUnionWithElements($elem1, $elem2) {
		$s1 = $this->funSets->singletonSet($elem1);
		$s2 = $this->funSets->singletonSet($elem2);
		return $this->funSets->union($s1, $s2);
	}

	public function createUnionWith123() {
		$u12 = $this->createUnionWithElements(1, 2);
		return $this->funSets->union($u12, $this->funSets->singletonSet(3));
	}

}

?>
