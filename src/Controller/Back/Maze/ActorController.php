<?php

namespace App\Controller\Back\Maze;

use App\Domain\Command\Maze\AddActorCommand;
use App\Entity\Maze\Actor;
use App\Enum\PagerEnum;
use App\Enum\Tmdb\TypeEnum;
use App\Repository\Maze\ActorRepository;
use App\Repository\Tmdb\BuildProcessRepository;
use App\Service\Handler\Maze\AddActorHandler;
use App\Service\Tmdb\DisplayableResultAdapter;
use App\Service\Tmdb\TmdbDataProvider;
use App\Validator\Tmdb\ActorValidator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ActorController extends AbstractController
{
    /**
     * @Route("/admin/maze/actor/list", name="bo_maze_actor_list")
     */
    public function listAction(
        Request $request,
        ActorRepository $actorRepository,
        BuildProcessRepository $buildProcessRepository
    ): Response {
        $page = $request->query->get('page', 1);
        $fullname = $request->query->get('value', null);

        return $this->render('back/maze/actor/list.html.twig', [
            'pager' => $actorRepository->getPager($fullname, $page, PagerEnum::DEFAULT_MAX_PER_PAGE),
            'value' => $fullname,
            'pendingProcess' => $buildProcessRepository->findPendingProcess(),
        ]);
    }

    /**
     * @Route("/admin/maze/actor/view/{actor}", requirements={"actor" = "\d+"}, name="bo_maze_actor_view")
     */
    public function viewAction(Actor $actor): Response
    {
        return $this->render('back/maze/actor/view.html.twig', ['actor' => $actor]);
    }

    /**
     * @Route("/admin/maze/actor/new", name="bo_maze_actor_new")
     */
    public function newAction(UrlGeneratorInterface $urlGenerator): Response
    {
        return $this->render('back/tmdb/search.html.twig', [
            'type' => TypeEnum::ACTOR,
            'searchUrl' => $urlGenerator->generate('bo_maze_actor_search'),
        ]);
    }

    /**
     * @Route("/admin/maze/actor/add/{tmdbId}", requirements={"tmdbId" = "\d+"}, name="bo_maze_actor_add")
     */
    public function addAction(
        TranslatorInterface $translator,
        AddActorHandler $handler,
        int $tmdbId
    ): Response {
        try {
            $handler->handle(new AddActorCommand($tmdbId));
            $this->addFlash('info', $translator->trans('back.global.add.success'));
        } catch (Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.add.error'));
        }

        return $this->redirectToRoute('bo_maze_actor_new');
    }

    /**
     * @Route("/admin/maze/actor/search", name="bo_maze_actor_search")
     */
    public function searchAction(
        Request $request,
        TmdbDataProvider $tmdbDataProvider,
        DisplayableResultAdapter $displayableResultAdapter
    ): Response {
        $name = $request->query->get('value', '');
        $actors = [];

        if (strlen($name) > 2) {
            $actors = $tmdbDataProvider->searchActors($name, new ActorValidator());
        }

        return $this->render('back/tmdb/search_result.html.twig', [
            'items' => $displayableResultAdapter->adaptArray($actors),
            'callback' => 'bo_maze_actor_add',
        ]);
    }

    /**
     * @Route("/admin/maze/actor/delete/{actor}", requirements={"actor" = "\d+"}, name="bo_maze_actor_delete")
     */
    public function deleteAction(
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        Actor $actor
    ): Response {
        try {
            $entityManager->remove($actor);
            $entityManager->flush();

            $this->addFlash('info', $translator->trans('back.global.delete.success'));
        } catch (Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.delete.error'));
        }

        return $this->redirectToRoute('bo_maze_actor_list');
    }
}
