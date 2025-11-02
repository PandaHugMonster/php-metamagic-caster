<?php

use spaf\metamagic\enums\TargetType;
use spaf\metamagic\exceptions\ClassReferenceException;
use spaf\metamagic\MetaMagic;
use spaf\metamagic\spells\SpellMethod;
use function spaf\simputils\basic\pd;

include_once "vendor/autoload.php";

/**
 * Attribute to mark methods that will perform "CastFrom" functionality.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class CastFrom {
	/**
	 * @param class-string|string $type
	 */
	function __construct(
		public string $type,
	) {}
}


/**
 * Attribute to mark methods that will perform "CastTo" functionality.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class CastTo {
	/**
	 * @param class-string|string $type
	 */
	function __construct(
		public string $type,
	) {}
}

/**
 * Helper function for easy casting/converting types and data.
 *
 * @param mixed $from
 * @param string $to
 * @return mixed
 * @throws ClassReferenceException
 */
function cast(mixed $from, string $to): mixed {

	/** @var SpellMethod $spell */
	$spell = MetaMagic::findSpellOne(
		refs: $from,
		attrs: CastTo::class,
		types: TargetType::MethodType,
		filter: function (SpellMethod $spell) use ($to) {
			/** @var CastTo $attr */
			$attr = $spell->attr;
			if ($attr->type == $to) {
				return $spell;
			}

			return false;
		}
	);
	if ($spell) {
		return $spell($to);
	}
}

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
	private function _castFromMyObj2(MyObj2 $from): static {
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

