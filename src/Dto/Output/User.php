<?php

namespace App\Dto\Output;

use App\Entity\User as UserEntity;


final class User
{

    public string $email;
    public static function fromModel(UserEntity $user): self
    {
        $output = new self();

        $output->email = $user->getEmail();
        return $output;
    }
}