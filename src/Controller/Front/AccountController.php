<?php

namespace App\Controller\Front;

use App\Domain\Command\User\AddUserCommand;
use App\Domain\Command\User\UpdateUserCommand;
use App\Entity\User;
use App\Enum\User\Roles;
use App\Form\AccountType;
use App\Repository\Quiz\QuizRepository;
use App\Service\Handler\User\AddUserHandler;
use App\Service\Handler\User\UpdateUserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AccountController extends Controller
{
    /**
     * @Route("/creer-compte", name="fo_account_new")
     *
     * @param Request $request
     * @param AddUserHandler $handler
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request, TokenStorageInterface $tokenStorageInterface, AddUserHandler $handler)
    {
        $user  = new User();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $newPassword = $form->get('newPassword')->getData();
                $user->addRole(Roles::ROLE_USER);
                $user->setUsername($user->getEmail());
                $handler->handle(new AddUserCommand($user, $newPassword));

                // Log user automatically
                $tokenStorageInterface->setToken(new UsernamePasswordToken(
                    $user,
                    null,
                    'user_service',
                    $user->getRoles()
                ));

                $this->addFlash('info', 'Votre compte a bien été créé');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la création de votre compte');
            }

            return $this->redirectToRoute('fo_home');
        }

        return $this->render('front/account/new.html.twig', ['account' => $form->createView()]);
    }

    /**
     * @Route("/mon-compte", name="fo_account_infos")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param Request $request
     * @param UpdateUserHandler $handler
     * @return RedirectResponse|Response
     */
    public function infosAction(Request $request, QuizRepository $quizRepository, UpdateUserHandler $handler)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user, ['ignore_email' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $newPassword = $form->get('newPassword')->getData();
                $handler->handle(new UpdateUserCommand($user, $newPassword));

                $this->addFlash('info', 'Votre compte a bien été mis à jour');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la mise à jour de votre compte sauvegarde');
            }

            return $this->redirectToRoute('fo_account_infos');
        }

        return $this->render('front/account/infos.html.twig', [
            'account' => $form->createView(),
            'quizInProgress' => $quizRepository->getQuizInProgressForUserId($user->getId()),
            'quizOver' => $quizRepository->getQuizOverForUserId($user->getId()),
        ]);
    }
}
