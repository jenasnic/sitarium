<?php

namespace App\EventListener\User;

use App\Event\UserEvents;
use App\Event\User\ResetPasswordEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ResetPasswordSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var \Twig_Environment
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
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     * @param string $mailerFrom
     * @param string $mailerSender
     */
    public function __construct(
        \Swift_Mailer $mailer,
        \Twig_Environment $twig,
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
            $mailMessage = new \Swift_Message();
            $mailMessage
                ->setSubject($subject)
                ->setFrom($this->mailerFrom, $this->mailerSender)
                ->setTo($event->getUser()->getEmail())
                ->setContentType('text/html')
                ->setBody($messageContent)
            ;

            $this->mailer->send($mailMessage);
        } catch (\Exception $e) {
        }
    }
}
