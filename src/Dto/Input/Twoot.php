<?php

namespace App\Dto\Input;

use App\Entity\Twoot as TwootEntity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class Twoot
{
    #[Assert\NotBlank]
    public string $twoot;

    public function toModel(TwootEntity $twoot, UserInterface $user): void
    {
        $twoot->setTwoot($this->twoot);
        $twoot->setUser($user);
        $twoot->setCreatedAt(new \DateTimeImmutable());
    }
}