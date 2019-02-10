<?php

namespace App\Controller\Back;

use App\Domain\Command\User\AddUserCommand;
use App\Domain\Command\User\DeleteUserCommand;
use App\Domain\Command\User\UpdateUserCommand;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\Handler\User\AddUserHandler;
use App\Service\Handler\User\DeleteUserHandler;
use App\Service\Handler\User\UpdateUserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends Controller
{
    /**
     * @Route("/admin/user/list", name="bo_user_list", methods="GET")
     *
     * @param UserRepository $userRepository
     *
     * @return Response
     */
    public function listAction(UserRepository $userRepository): Response
    {
        $users = $userRepository->findBy(
            [],
            [
                'lastname' => 'asc',
                'firstname' => 'asc',
            ]
        );

        return $this->render('back/user/list.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/admin/user/add", name="bo_user_add")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param AddUserHandler $handler
     *
     * @return Response
     */
    public function addAction(Request $request, TranslatorInterface $translator, AddUserHandler $handler): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $newPassword = $form->get('newPassword')->getData();
                $handler->handle(new AddUserCommand($user, $newPassword));

                $this->addFlash('info', $translator->trans('back.global.save.success'));
            } catch (\Exception $e) {
                $this->addFlash('error', $translator->trans('back.global.save.error'));
            }

            return $this->redirectToRoute('bo_user_list');
        }

        return $this->render('back/user/edit.html.twig', ['user' => $form->createView()]);
    }

    /**
     * @Route("/admin/user/edit/{user}", requirements={"user" = "\d+"}, name="bo_user_edit")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param UpdateUserHandler $handler
     * @param User $user
     *
     * @return Response
     */
    public function editAction(
        Request $request,
        TranslatorInterface $translator,
        UpdateUserHandler $handler,
        User $user
    ): Response {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $newPassword = $form->get('newPassword')->getData();
                $handler->handle(new UpdateUserCommand($user, $newPassword));

                $this->addFlash('info', $translator->trans('back.global.save.success'));
            } catch (\Exception $e) {
                $this->addFlash('error', $translator->trans('back.global.save.error'));
            }

            return $this->redirectToRoute('bo_user_list');
        }

        return $this->render('back/user/edit.html.twig', ['user' => $form->createView()]);
    }

    /**
     * @Route("/admin/user/delete/{user}", requirements={"user" = "\d+"}, name="bo_user_delete")
     *
     * @param TranslatorInterface $translator
     * @param DeleteUserHandler $handler
     * @param User $user
     *
     * @return Response
     */
    public function deleteAction(TranslatorInterface $translator, DeleteUserHandler $handler, User $user): Response
    {
        try {
            $handler->handle(new DeleteUserCommand($user));
            $this->addFlash('info', $translator->trans('back.global.delete.success'));
        } catch (\Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.delete.error'));
        }

        return $this->redirectToRoute('bo_user_list');
    }
}
