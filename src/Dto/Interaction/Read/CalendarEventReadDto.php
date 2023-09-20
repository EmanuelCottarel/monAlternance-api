<?php

namespace App\Dto\Interaction\Read;

class CalendarEventReadDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $start,
        public string $end,
        public array $extendedProps,
        public string $backgroundColor,
        public string $borderColor)
    {
    }
}