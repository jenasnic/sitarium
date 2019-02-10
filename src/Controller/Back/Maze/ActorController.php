<?php

namespace App\Controller\Back\Maze;

use App\Domain\Command\Maze\AddActorCommand;
use App\Entity\Maze\Actor;
use App\Enum\Tmdb\Types;
use App\Repository\Maze\ActorRepository;
use App\Service\Handler\Maze\AddActorHandler;
use App\Service\Tmdb\TmdbApiService;
use App\Validator\Maze\ActorValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ActorController extends Controller
{
    /**
     * Max actor count to return when searching actors through TMDB.
     *
     * @var int
     */
    const MAX_ACTOR_RESULT_COUNT = 10;

    /**
     * @Route("/admin/maze/actor/list", name="bo_maze_actor_list")
     *
     * @param ActorRepository $actorRepository
     *
     * @return Response
     */
    public function listAction(ActorRepository $actorRepository): Response
    {
        return $this->render('back/maze/actor/list.html.twig', [
            'actors' => $actorRepository->findBy([], ['fullname' => 'asc']),
        ]);
    }

    /**
     * @Route("/admin/maze/actor/view/{actor}", requirements={"actor" = "\d+"}, name="bo_maze_actor_view")
     *
     * @param Actor $actor
     *
     * @return Response
     */
    public function viewAction(Actor $actor): Response
    {
        return $this->render('back/maze/actor/view.html.twig', ['actor' => $actor]);
    }

    /**
     * @Route("/admin/maze/actor/new", name="bo_maze_actor_new")
     *
     * @param UrlGeneratorInterface $urlGenerator
     *
     * @return Response
     */
    public function newAction(UrlGeneratorInterface $urlGenerator): Response
    {
        return $this->render('back/tmdb/search.html.twig', [
            'type' => Types::ACTOR,
            'searchUrl' => $urlGenerator->generate('bo_maze_actor_search')
        ]);
    }

    /**
     * @Route("/admin/maze/actor/add/{tmdbId}", requirements={"tmdbId" = "\d+"}, name="bo_maze_actor_add")
     *
     * @param TranslatorInterface $translator
     * @param AddActorHandler $handler
     * @param int $tmdbId
     *
     * @return Response
     */
    public function addAction(
        TranslatorInterface $translator,
        AddActorHandler $handler,
        int $tmdbId
    ): Response {
        try {
            $handler->handle(new AddActorCommand($tmdbId));
            $this->addFlash('info', $translator->trans('back.global.add.success'));
        } catch (\Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.add.error'));
        }

        return $this->redirectToRoute('bo_maze_actor_new');
    }

    /**
     * @Route("/admin/maze/actor/search", name="bo_maze_actor_search")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param TmdbApiService $tmdbService
     *
     * @return Response
     */
    public function searchAction(
        Request $request,
        TranslatorInterface $translator,
        TmdbApiService $tmdbService
    ): Response {
        $name = $request->query->get('value', '');
        $actors = [];

        if (strlen($name) > 2) {
            $result = $tmdbService->searchEntity(Actor::class, $name, new ActorValidator(), self::MAX_ACTOR_RESULT_COUNT);
            $actors = $result['results'];
        }

        return $this->render('back/tmdb/search_result.html.twig', [
            'items' => $actors,
            'callback' => 'bo_maze_actor_add',
        ]);
    }

    /**
     * @Route("/admin/maze/actor/delete/{actor}", requirements={"actor" = "\d+"}, name="bo_maze_actor_delete")
     *
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     * @param Actor $actor
     *
     * @return Response
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
        } catch (\Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.delete.error'));
        }

        return $this->redirectToRoute('bo_maze_actor_list');
    }
}
