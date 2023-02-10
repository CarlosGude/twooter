<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserPasswordListener implements EventSubscriber
{

    public function __construct(protected UserPasswordHasherInterface $encoder)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $event): void
    {
        $this->updatePassword($event);
    }

    public function preUpdate(LifecycleEventArgs $event): void
    {
        $this->updatePassword($event);
    }

    protected function updatePassword(LifecycleEventArgs $event): void
    {
        if (null === $this->encoder) {
            return;
        }
        $entity = $event->getEntity();

        if (($entity instanceof User) && null !== $entity->getPassword()) {
            $password = $this->encoder->hashPassword($entity,$entity->getPassword());
            $entity->setPassword($password);
            $entity->eraseCredentials();
        }
    }
}
