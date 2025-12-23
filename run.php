<?php

use spaf\metamagic\caster\attrs\CastFrom;
use spaf\metamagic\caster\attrs\CastTo;
use function spaf\simputils\basic\pd;

include_once "vendor/autoload.php";


class MyObj1 {
	public function __construct(
		public int $a = 0,
		public int $b = 0,
		public float $c = 0,
	) {}

	#[CastTo(Exception::class)]
	private function _rubbishA() {}

	#[CastTo(MyObj2::class)]
	private function _castToMyObj2(): MyObj2 {
		return new MyObj2(
			value: floatval($this->a + $this->b + $this->c)
		);
	}

	#[CastTo(Exception::class)]
	private function _rubbishB() {}


	#[CastFrom(Exception::class)]
	private function _rubbishC() {}

	#[CastFrom(MyObj2::class)]
    static private function _castFromMyObj2(MyObj2 $from): static {
		$self = static::class;

		return new $self(
			a: intval($from->value / 3),
			b: intval($from->value / 3),
			c: floatval($from->value / 3),
		);
	}


	#[CastFrom(Exception::class)]
	private function _rubbishD() {}
}

class MyObj2 {
	public function __construct(
		public float $value = 0.0,
	) {}
}

$obj1 = new MyObj1(1, 2, 3);

$obj2 = cast($obj1, MyObj2::class);
$obj3 = cast($obj2, MyObj1::class);

pd($obj1, $obj2, $obj3);

