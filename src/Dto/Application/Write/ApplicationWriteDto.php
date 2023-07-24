<?php

namespace App\Dto\Application\Write;

class ApplicationWriteDto
{
    public function __construct(
        public ?int $id,
        public string $companyName,
        public \DateTimeImmutable $submitedAt,
        public string $email,
        public string $phoneNumber,
        public string $webSite,
        public string $status
    ){

    }
}