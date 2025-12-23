<?php

namespace spaf\metamagic\caster\attrs;

use Attribute;

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
