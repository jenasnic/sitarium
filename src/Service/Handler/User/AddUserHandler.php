<?php

namespace App\Service\Handler\User;

use App\Domain\Command\User\AddUserCommand;
use App\Event\UserEvents;
use App\Event\User\NewAccountEvent;
use App\Tool\PasswordUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Allows to add user in current database.
 */
class AddUserHandler
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param AddUserCommand $command
     */
    public function handle(AddUserCommand $command)
    {
        $password = $command->getPassword();
        if (null === $password) {
            $password = PasswordUtil::generatePassword(6, true, true, true, false);
        }

        $user = $command->getUser();
        $user->setPassword(PasswordUtil::encodePassword($password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(UserEvents::NEW_ACCOUNT, new NewAccountEvent($user, $password));
    }
}
