<?php

class FunSets {

	public function contains($set, $elem) {
		return $set($elem);
	}

	public function singletonSet($elem) {
		return function ($otherElem) use ($elem) {
					return $elem == $otherElem;
				};
	}

	public function union($s1, $s2) {
		return function ($otherElem) use ($s1, $s2) {
					return $this->contains($s1, $otherElem) || $this->contains($s2, $otherElem);
				};
	}

	public function intersect($s1, $s2) {
		return function ($otherElem) use ($s1, $s2) {
					return $this->contains($s1, $otherElem) && $this->contains($s2, $otherElem);
				};
	}

	public function diff($s1, $s2) {
		return function ($otherElem) use ($s1, $s2) {
					return $this->contains($s1, $otherElem) && !$this->contains($s2, $otherElem);
				};
	}

	public function filter($set, $condition) {
		return function ($otherElem) use ($set, $condition) {
					if ($condition($otherElem))
						return $this->contains($set, $otherElem);
					return false;
				};
	}

	// We need to set some reasonable limits for so that our iterations will always exit.
	private $bound = 1000;

	private function forallIterator($currentValue, $set, $condition) {
		if ($currentValue > $this->bound)
			return true;
		elseif ($this->contains($set, $currentValue))
			return $condition($currentValue) && $this->forallIterator($currentValue + 1, $set, $condition);
		else
			return $this->forallIterator($currentValue + 1, $set, $condition);
	}

	public function forall($set, $condition) {
		return $this->forallIterator(-$this->bound, $set, $condition);
	}

	private function existsIterator($currentValue, $set, $condition) {
		if ($currentValue > $this->bound)
			return false;
		elseif ($this->contains($set, $currentValue))
			return $condition($currentValue) || $this->existsIterator($currentValue + 1, $set, $condition);
		else
			return $this->existsIterator($currentValue + 1, $set, $condition);
	}

	public function exists($set, $condition) {
		return $this->existsIterator(-$this->bound, $set, $condition);
	}

	public function map($set, $action) {
		return function ($currentElem) use ($set, $action) {
			return $this->exists($set, function($elem) use ($currentElem, $action) {
				return $currentElem == $action($elem);
			});
		};
	}

}

?>
