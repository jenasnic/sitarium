<?php

namespace App\Service\Handler\User;

use App\Domain\Command\User\AddUserCommand;
use App\Event\User\NewAccountEvent;
use App\Tool\PasswordUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Allows to add user in current database.
 */
class AddUserHandler
{
    protected EntityManagerInterface $entityManager;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(AddUserCommand $command): void
    {
        $password = $command->getPassword();
        if (null === $password) {
            $password = PasswordUtil::generatePassword(6, true, true, true, false);
        }

        $user = $command->getUser();
        $user->setPassword(PasswordUtil::encodePassword($password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new NewAccountEvent($user, $password), NewAccountEvent::NEW_ACCOUNT);
    }
}
