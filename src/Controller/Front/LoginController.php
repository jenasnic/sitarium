<?php

namespace App\Controller\Front;

use App\Domain\Command\User\ResetPasswordCommand;
use App\Repository\UserRepository;
use App\Service\Handler\User\ResetUserPasswordHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginController extends AbstractController
{
    /**
     * @Route("/connexion", name="login", methods="GET")
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('front/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/mot-de-passe-oublie", name="fo_forgotten_password")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param UserRepository $userRepository
     * @param ResetUserPasswordHandler $handler
     *
     * @return Response
     */
    public function infosAction(
        Request $request,
        TranslatorInterface $translator,
        UserRepository $userRepository,
        ResetUserPasswordHandler $handler
    ): Response {
        if ($request->isMethod(Request::METHOD_POST)) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneBy(['email' => $email]);

            if (null !== $user) {
                try {
                    $handler->handle(new ResetPasswordCommand($user));
                    $this->addFlash('info', $translator->trans('front.login.password.reset.send'));

                    return $this->redirectToRoute('login');
                } catch (\Exception $e) {
                    $this->addFlash('error', $translator->trans('front.login.password.reset.error'));
                }
            } else {
                $this->addFlash('warning', $translator->trans('front.login.password.reset.user_not_found'));
            }
        }

        return $this->render('front/account/forgotten-password.html.twig');
    }
}
