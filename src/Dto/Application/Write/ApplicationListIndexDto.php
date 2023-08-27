<?php

namespace App\Dto\Application\Write;

class ApplicationListIndexDto
{
    public function __construct(
        public int $lastIndex,
        public int $newIndex

    ){

    }
}