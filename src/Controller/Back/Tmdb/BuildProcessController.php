<?php

namespace App\Controller\Back\Tmdb;

use App\Enum\Tmdb\ProcessTypeEnum;
use App\Repository\Tmdb\BuildProcessRepository;
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
     * @Route("/admin/tmdb/build/process", name="bo_tmdb_build_process", methods="POST")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param BuildProcessRepository $buildProcessRepository
     * @param string $rootDir
     *
     * @return Response
     */
    public function buildProcessAction(
        Request $request,
        TranslatorInterface $translator,
        BuildProcessRepository $buildProcessRepository,
        string $rootDir
    ): Response {
        $type = $request->request->get('type');
        $redirect = $request->request->get('redirect');

        if (!ProcessTypeEnum::exists($type) || null === $redirect) {
            throw new \InvalidArgumentException(sprintf('Invalid parameters (type "%s" / redirect "%s")', $type, $redirect));
        }

        $pendingProcess = $buildProcessRepository->findPendingProcess();

        if (null !== $pendingProcess) {
            $message = $translator->trans(sprintf(
                'back.tmdb.build.process.%s.already_pending',
                $pendingProcess->getType()
            ));
            $this->addFlash('warning', $message);
        } else {
            $process = Process::fromShellCommandline(sprintf('nohup bin/console tmdb:build:%s &', $type), $rootDir);
            $process->start();

            $message = $translator->trans(sprintf('back.tmdb.build.process.%s.start', $type));
            $this->addFlash('info', $message);
        }

        return $this->redirect($redirect);
    }

    /**
     * @Route("/admin/maze/build/progress", name="bo_tmdb_build_progress")
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
