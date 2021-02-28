<?php

namespace App\Service\Handler\User;

use App\Domain\Command\User\UpdateUserCommand;
use App\Event\User\UpdateAccountEvent;
use App\Tool\PasswordUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Allows to update user in current database.
 */
class UpdateUserHandler
{
    protected EntityManagerInterface $entityManager;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(UpdateUserCommand $command): void
    {
        $user = $command->getUser();
        $password = $command->getPassword();

        if (null !== $password) {
            $user->setPassword(PasswordUtil::encodePassword($password));
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new UpdateAccountEvent($user, $password), UpdateAccountEvent::UPDATE_ACCOUNT);
    }
}
