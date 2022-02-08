<?php

namespace App\Doctrine\Listener;

use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener
{
    private UserPasswordHasherInterface $pe;

    public function __construct(UserPasswordHasherInterface $pe)
    {
        $this->pe = $pe;
    }

    public function prePersist(User $user): void
    {
        if ($user->getPlainPassword()) {
            $this->rehashUserPasswordField($user, $user->getPlainPassword());
        }
    }

    public function preUpdate(User $user, LifecycleEventArgs $event): void
    {
        if ($user->getPlainPassword()) {
            $this->rehashUserPasswordField($user, $user->getPlainPassword());
            $meta = $event->getObjectManager()->getClassMetadata(get_class($user));
            $event->getObjectManager()->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $user);
        }
    }

    private function rehashUserPasswordField(User $user, string $plainPassword): void
    {
        $user
            ->setSalt('unsalted')
            ->setPassword($this->pe->hashPassword($user, $plainPassword))
            ->eraseCredentials()
        ;
    }
}
