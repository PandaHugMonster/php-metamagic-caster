<?php

use spaf\metamagic\caster\attrs\CastFrom;
use spaf\metamagic\caster\exceptions\UncastableException;
use spaf\metamagic\enums\TargetType;
use spaf\metamagic\exceptions\ClassReferenceException;
use spaf\metamagic\MetaMagic;
use spaf\metamagic\spells\SpellMethod;
use spaf\metamagic\caster\attrs\CastTo;


/**
 * Helper function for easy casting/converting types and data.
 *
 * @param mixed $from
 * @param string $to
 * @return mixed
 * @throws ClassReferenceException
 * @throws UncastableException Thrown in case value could not be casted
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
    /** @var SpellMethod $spell */

    $spell = MetaMagic::findSpellOne(
        refs: $to,
        attrs: CastFrom::class,
        types: TargetType::MethodType,
        filter: function (SpellMethod $spell) use ($from) {
            /** @var CastFrom $attr */
            $attr = $spell->attr;
            if (is_object($from) and $attr->type == $from::class) {
                return $spell;
            }

            return false;
        }
    );
    if ($spell) {
        return $spell($from);
    }

    throw new UncastableException("Value \"".gettype($from)."\" could not be casted into \"".gettype($to)."\"");
}
