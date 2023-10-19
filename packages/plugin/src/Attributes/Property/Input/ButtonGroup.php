<?php

namespace Solspace\Freeform\Attributes\Property\Input;

use Solspace\Freeform\Attributes\Property\Implementations\Options\OptionCollection;
use Solspace\Freeform\Attributes\Property\Property;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ButtonGroup extends Property implements OptionsInterface
{
    public ?string $type = 'buttonGroup';

    public function __construct(
        ?string $label = null,
        ?string $instructions = null,
        ?int $order = null,
        string|int|null $value = null,
        ?int $width = null,
        public OptionCollection|array|string|null $options = null,
    ) {
        parent::__construct(
            label: $label,
            instructions: $instructions,
            order: $order,
            value: $value,
            width: $width,
        );
    }
}
