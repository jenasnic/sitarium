<?php

namespace App\EventListener\User;

use App\Event\UserEvents;
use App\Event\User\ResetPasswordEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class ResetPasswordSubscriber implements EventSubscriberInterface
{
    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var string
     */
    protected $mailerFrom;

    /**
     * @var string
     */
    protected $mailerSender;

    /**
     * @param MailerInterface $mailer
     * @param Environment $twig
     * @param string $mailerFrom
     * @param string $mailerSender
     */
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
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvents::RESET_PASSWORD => 'onResetPassword',
        ];
    }

    /**
     * @param ResetPasswordEvent $event
     */
    public function onResetPassword(ResetPasswordEvent $event)
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
        } catch (\Exception $e) {
        }
    }
}
