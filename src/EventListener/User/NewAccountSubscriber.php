<?php

namespace App\EventListener\User;

use App\Event\User\NewAccountEvent;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class NewAccountSubscriber implements EventSubscriberInterface
{
    protected MailerInterface $mailer;

    protected Environment $twig;

    protected string $mailerFrom;

    protected string $mailerSender;

    public function __construct(
        MailerInterface $mailer,
        Environment $twig,
        string $mailerFrom,
        string $mailerSender
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->mailerFrom = $mailerFrom;
        $this->mailerSender = $mailerSender;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NewAccountEvent::NEW_ACCOUNT => 'onNewAccount',
        ];
    }

    public function onNewAccount(NewAccountEvent $event): void
    {
        try {
            $subject = 'Bienvenue '.$event->getUser()->getDisplayName();

            $messageContent = $this->twig->render('mail/new_account.html.twig', [
                'user' => $event->getUser(),
                'password' => $event->getPassword(),
            ]);

            // Create mail and send it
            $mailMessage = new Email();
            $mailMessage
                ->subject($subject)
                ->from($this->mailerFrom, $this->mailerSender)
                ->to($event->getUser()->getEmail())
                ->html($messageContent)
            ;

            $this->mailer->send($mailMessage);
        } catch (Exception $e) {
        }
    }
}
