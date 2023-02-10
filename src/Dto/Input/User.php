<?php

namespace App\Dto\Input;

use App\Entity\User as UserEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class User
{
    #[Assert\Email]
    #[Groups(['user:create', 'user:update'])]
    public string $email;

    #[Assert\NotBlank]
    #[Groups(['user:create', 'user:update'])]
    public string $password;

    public function toModel(UserEntity $user): void
    {
        $user->setEmail($this->email);
        $user->setPassword($this->password);
    }
}