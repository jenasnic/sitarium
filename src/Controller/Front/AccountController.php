<?php

namespace App\Controller\Front;

use App\Domain\Command\User\AddUserCommand;
use App\Domain\Command\User\UpdateUserCommand;
use App\Entity\User;
use App\Enum\User\RoleEnum;
use App\Form\AccountType;
use App\Repository\Quiz\QuizRepository;
use App\Service\Handler\User\AddUserHandler;
use App\Service\Handler\User\UpdateUserHandler;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/creer-compte", name="fo_account_new")
     */
    public function newAction(
        Request $request,
        TranslatorInterface $translator,
        TokenStorageInterface $tokenStorageInterface,
        AddUserHandler $handler
    ): Response {
        $user = new User();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $newPassword = $form->get('newPassword')->getData();
                $user->addRole(RoleEnum::ROLE_USER);
                $user->setUsername($user->getEmail());
                $handler->handle(new AddUserCommand($user, $newPassword));

                // Log user automatically
                $tokenStorageInterface->setToken(new UsernamePasswordToken(
                    $user,
                    null,
                    'user_service',
                    $user->getRoles()
                ));

                $this->addFlash('info', $translator->trans('front.account.create.success'));
            } catch (Exception $e) {
                $this->addFlash('error', $translator->trans('front.account.create.error'));
            }

            return $this->redirectToRoute('fo_home');
        }

        return $this->render('front/account/new.html.twig', ['account' => $form->createView()]);
    }

    /**
     * @Route("/mon-compte", name="fo_account_infos")
     * @Security("is_granted('ROLE_USER')")
     */
    public function infosAction(
        Request $request,
        TranslatorInterface $translator,
        QuizRepository $quizRepository,
        UpdateUserHandler $handler
    ): Response {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user, ['ignore_email' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $newPassword = $form->get('newPassword')->getData();
                $handler->handle(new UpdateUserCommand($user, $newPassword));

                $this->addFlash('info', $translator->trans('front.account.update.success'));
            } catch (Exception $e) {
                $this->addFlash('error', $translator->trans('front.account.update.error'));
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
