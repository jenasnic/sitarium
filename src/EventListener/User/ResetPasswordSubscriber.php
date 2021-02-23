<?php

namespace App\EventListener\User;

use App\Event\User\ResetPasswordEvent;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class ResetPasswordSubscriber implements EventSubscriberInterface
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

    public static function getSubscribedEvents(): array
    {
        return [
            ResetPasswordEvent::RESET_PASSWORD => 'onResetPassword',
        ];
    }

    public function onResetPassword(ResetPasswordEvent $event): void
    {
        try {
            $subject = 'Mot de passe oublié';

            $messageContent = $this->twig->render('mail/reset_password.html.twig', [
                'user' => $event->getUser(),
                'password' => $event->getPassword(),
            ]);

            // Create mail and send it
            $mailMessage = new Email();
            $mailMessage
                ->setSubject($subject)
                ->setFrom($this->mailerFrom, $this->mailerSender)
                ->setTo($event->getUser()->getEmail())
                ->setBody($messageContent, 'text/html')
            ;

            $this->mailer->send($mailMessage);
        } catch (Exception $e) {
        }
    }
}
