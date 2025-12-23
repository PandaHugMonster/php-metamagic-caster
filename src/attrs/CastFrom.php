<?php

namespace spaf\metamagic\caster\attrs;

use Attribute;

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
