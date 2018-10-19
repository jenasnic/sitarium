<?php

namespace App\Controller\Back\Maze;

use App\Domain\Command\Maze\AddActorCommand;
use App\Entity\Maze\Actor;
use App\Repository\Maze\ActorRepository;
use App\Service\Handler\Maze\AddActorHandler;
use App\Service\Tmdb\TmdbApiService;
use App\Validator\Maze\ActorValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActorController extends Controller
{
    /**
     * Max actor count to return when searching actors through TMDB.
     *
     * @var integer
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
            'actors' => $actorRepository->findBy([], ['fullname' => 'asc'])
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
     * @Route("/admin/maze/actor/add/{tmdbId}", requirements={"tmdbId" = "\d+"}, defaults={"tmdbId" = 0}, name="bo_maze_actor_add")
     *
     * @param AddActorHandler $handler
     * @param int $tmdbId
     *
     * @return Response
     */
    public function addAction(AddActorHandler $handler, int $tmdbId): Response
    {
        // If no ID => allows user to search actor using TMDB API
        if (0 === $tmdbId) {
            return $this->render('back/maze/actor/add.html.twig');
        }

        try {
            $handler->handle(new AddActorCommand($tmdbId));
            $this->addFlash('info', 'L\'acteur a bien été ajouté à la liste');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de l\'ajout');
        }

        return $this->redirectToRoute('bo_maze_actor_add');
    }

    /**
     * @Route("/admin/maze/actor/search/{name}", name="bo_maze_actor_search")
     *
     * @param TmdbApiService $tmdbService
     * @param string $name
     *
     * @return Response
     */
    public function searchAction(TmdbApiService $tmdbService, string $name): Response
    {
        $actors = [];

        if (strlen($name) > 2) {
            try {
                $result = $tmdbService->searchEntity(Actor::class, $name, new ActorValidator(), self::MAX_ACTOR_RESULT_COUNT);
                $actors = $result['results'];
            } catch (\Exception $e) {
                return new Response('Erreur lors de la recherche', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $this->render('back/maze/actor/search.html.twig', ['actors' => $actors]);
    }

    /**
     * @Route("/admin/maze/actor/delete/{actor}", requirements={"actor" = "\d+"}, name="bo_maze_actor_delete")
     *
     * @param EntityManagerInterface $entityManager
     * @param Actor $actor
     *
     * @return Response
     */
    public function deleteAction(EntityManagerInterface $entityManager, Actor $actor): Response
    {
        try {
            $entityManager->remove($actor);
            $entityManager->flush();

            $this->addFlash('info', 'Suppression OK');
        } catch (\Exception $e) {
            $this->add('error', 'Erreur lors de la suppression');
        }

        return $this->redirectToRoute('bo_maze_actor_list');
    }
}
