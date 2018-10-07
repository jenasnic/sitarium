<?php

namespace App\Controller\Back;

use App\Domain\Command\User\DeleteUserCommand;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Handler\User\DeleteUserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use App\Service\Handler\User\UpdateUserHandler;
use App\Domain\Command\User\UpdateUserCommand;
use App\Service\Handler\User\AddUserHandler;
use App\Domain\Command\User\AddUserCommand;

class UserController extends Controller
{
    /**
     * @Route("/admin/user/list", name="bo_user_list", methods="GET")
     *
     * @param UserRepository $userRepository
     *
     * @return Response
     */
    public function listAction(UserRepository $userRepository)
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
     * @param AddUserHandler $handler
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request, AddUserHandler $handler)
    {
        $user  = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $newPassword = $form->get('newPassword')->getData();
                $handler->handle(new AddUserCommand($user, $newPassword));

                $this->addFlash('info', 'Sauvegarde OK');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la sauvegarde');
            }

            return $this->redirectToRoute('bo_user_list');
        }

        return $this->render('back/user/edit.html.twig', ['user' => $form->createView()]);
    }

    /**
     * @Route("/admin/user/edit/{user}", requirements={"user" = "\d+"}, name="bo_user_edit")
     *
     * @param Request $request
     * @param UpdateUserHandler $handler
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, UpdateUserHandler $handler, User $user)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $newPassword = $form->get('newPassword')->getData();
                $handler->handle(new UpdateUserCommand($user, $newPassword));

                $this->addFlash('info', 'Sauvegarde OK');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la sauvegarde');
            }

            return $this->redirectToRoute('bo_user_list');
        }

        return $this->render('back/user/edit.html.twig', ['user' => $form->createView()]);
    }

    /**
     * @Route("/admin/user/delete/{user}", requirements={"user" = "\d+"}, name="bo_user_delete")
     *
     * @param DeleteUserHandler $handler
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(DeleteUserHandler $handler, User $user)
    {
        try {
            $handler->handle(new DeleteUserCommand($user));
            $this->addFlash('info', 'Suppression OK');
        }
        catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la suppression');
        }

        return $this->redirectToRoute('bo_user_list');
    }
}
