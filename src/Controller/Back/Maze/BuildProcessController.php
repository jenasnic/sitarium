<?php

namespace App\Controller\Back\Maze;

use App\Enum\Maze\ProcessTypeEnum;
use App\Repository\Maze\BuildProcessRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class BuildProcessController extends AbstractController
{
    /**
     * @Route("/admin/maze/build/casting", name="bo_maze_build_casting", defaults={"type": "casting"}, methods="POST")
     * @Route("/admin/maze/build/filmography", name="bo_maze_build_filmography", defaults={"type": "filmography"}, methods="POST")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param BuildProcessRepository $buildProcessRepository
     * @param string $rootDir
     * @param string $type
     *
     * @return Response
     */
    public function buildProcessAction(
        Request $request,
        TranslatorInterface $translator,
        BuildProcessRepository $buildProcessRepository,
        string $rootDir,
        string $type
    ): Response {
        $pendingProcess = $buildProcessRepository->findPendingProcess();

        if (null !== $pendingProcess) {
            $message = $translator->trans(sprintf(
                'back.maze.build.process.%s.already_pending',
                $pendingProcess->getType()
            ));
            $this->addFlash('warning', $message);
        } else {
            $command = ProcessTypeEnum::CASTING === $type ? 'maze:build:casting' : 'maze:build:filmography';
            $process = Process::fromShellCommandline(sprintf('nohup bin/console %s &', $command), $rootDir);
            $process->start();

            $message = $translator->trans(sprintf('back.maze.build.process.%s.start', $type));
            $this->addFlash('success', $message);
        }

        $redirectRoute = ProcessTypeEnum::CASTING === $type ? 'bo_maze_movie_list' :'bo_maze_actor_list';

        return $this->redirectToRoute($redirectRoute);
    }

    /**
     * @Route("/admin/maze/build/progress", name="bo_maze_build_progress")
     *
     * @param BuildProcessRepository $buildProcessRepository
     *
     * @return JsonResponse
     */
    public function castingProgressAction(BuildProcessRepository $buildProcessRepository): JsonResponse
    {
        $pendingProcess = $buildProcessRepository->findPendingProcess();

        if (null === $pendingProcess) {
            return new JsonResponse([
                'current' => 0,
                'total' => 0,
            ]);
        }

        return new JsonResponse([
            'current' => $pendingProcess->getCount(),
            'total' => $pendingProcess->getTotal(),
        ]);
    }
}
