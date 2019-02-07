<?php

namespace App\Controller\Front;

use App\Domain\Command\User\ResetPasswordCommand;
use App\Repository\UserRepository;
use App\Service\Handler\User\ResetUserPasswordHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends Controller
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
     * @param ResetUserPasswordHandler $handler
     *
     * @return Response
     */
    public function infosAction(Request $request, UserRepository $userRepository, ResetUserPasswordHandler $handler): Response
    {
        if ($request->isMethod(Request::METHOD_POST)) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneBy(['email' => $email]);

            if (null !== $user) {
                try {
                    $handler->handle(new ResetPasswordCommand($user));
                    $this->addFlash('info', 'Votre nouveau mot de passe vous a été envoyé par mail.');

                    return $this->redirectToRoute('login');
                }
                catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors du renouvellement de mot de passe.');
                }
            } else {
                $this->addFlash('warning', 'Aucun utilisateur trouvé pour l\'email saisie.');
            }
        }

        return $this->render('front/account/forgotten-password.html.twig');
    }
}
