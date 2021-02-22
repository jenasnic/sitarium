<?php

namespace App\Service\Handler\User;

use App\Domain\Command\User\ResetPasswordCommand;
use App\Event\User\ResetPasswordEvent;
use App\Tool\PasswordUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Allows to reset user password : generate new password and send it by email.
 */
class ResetUserPasswordHandler
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
     * @param ResetPasswordCommand $command
     */
    public function handle(ResetPasswordCommand $command)
    {
        $password = PasswordUtil::generatePassword(6, true, true, true, false);

        $user = $command->getUser();
        $user->setPassword(PasswordUtil::encodePassword($password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new ResetPasswordEvent($user, $password), ResetPasswordEvent::RESET_PASSWORD);
    }
}
