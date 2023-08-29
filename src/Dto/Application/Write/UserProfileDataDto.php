<?php

namespace App\Dto\Application\Write;

class UserProfileDataDto
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email

    ){

    }
}