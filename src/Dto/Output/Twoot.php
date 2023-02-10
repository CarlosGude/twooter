<?php

namespace App\Dto\Output;

use App\Entity\Twoot as TwootEntity;

final class Twoot
{
    public string $twoot;
    public User $user;

    public static function fromModel(TwootEntity $twoot): self
    {
        $output = new self();

        $output->twoot = $twoot->getTwoot();
        $output->user = User::fromModel($twoot->getUser());
        return $output;
    }
}