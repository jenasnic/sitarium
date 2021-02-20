<?php

namespace App\EventListener\User;

use App\Event\UserEvents;
use App\Event\User\NewAccountEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class NewAccountSubscriber implements EventSubscriberInterface
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
     * @param string $mailFrom
     * @param string $mailSender
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
            UserEvents::NEW_ACCOUNT => 'onNewAccount',
        ];
    }

    /**
     * @param NewAccountEvent $event
     */
    public function onNewAccount(NewAccountEvent $event)
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
