<?php

namespace App\Dto\Interaction\Read;

class InteractionReadDto
{
    public function __construct(
        public ?string $type,
        public string $date,
        public string $title,
    )
    {
    }
}