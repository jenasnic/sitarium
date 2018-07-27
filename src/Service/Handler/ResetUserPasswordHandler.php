<?php

namespace App\Service\Handler;

use App\Domain\Command\ResetUserPasswordCommand;
use App\Entity\User;
use App\Event\UserEvents;
use App\Event\User\ResetUserPasswordEvent;
use App\Tool\PasswordUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Allows to reset user password : generate new password and send it by email
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
     * @param ResetUserPasswordCommand $command
     */
    public function handle(ResetUserPasswordCommand $command)
    {
        $password = PasswordUtil::generatePassword(6, true, true, true, false);

        $user = $this->entityManager->getRepository(User::class)->find($command->getUserId());
        $user->setPassword(PasswordUtil::encodePassword($password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(UserEvents::RESET_PASSWORD, new ResetUserPasswordEvent($user, $password));
    }
}
