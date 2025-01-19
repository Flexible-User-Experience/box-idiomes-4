<?php

namespace App\Doctrine\Listener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(event: 'prePersist', entity: User::class, priority: 2)]
#[AsEntityListener(event: 'preUpdate', entity: User::class, priority: 2)]
final readonly class UserListener
{
    public function __construct(private UserPasswordHasherInterface $pe)
    {
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
