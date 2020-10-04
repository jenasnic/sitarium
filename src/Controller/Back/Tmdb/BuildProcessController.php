<?php

namespace App\Controller\Back\Tmdb;

use App\Domain\Command\Tmdb\ExecuteProcessCommand;
use App\Repository\Tmdb\BuildProcessRepository;
use App\Service\Handler\Tmdb\ExecuteProcessHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @param ExecuteProcessHandler $executeProcessHandler
     * @param ExecuteProcessCommand $executeProcessCommand
     *
     * @return Response
     */
    public function buildProcessAction(
        Request $request,
        TranslatorInterface $translator,
        BuildProcessRepository $buildProcessRepository,
        ExecuteProcessHandler $executeProcessHandler,
        ExecuteProcessCommand $executeProcessCommand
    ): Response {
        $redirect = $request->request->get('redirect');

        if (null === $redirect) {
            throw new \InvalidArgumentException(sprintf('Invalid redirect URL "%s"', $redirect));
        }

        $pendingProcess = $buildProcessRepository->findPendingProcess();

        if (null !== $pendingProcess) {
            $message = $translator->trans(sprintf(
                'back.tmdb.build.process.%s.already_pending',
                $pendingProcess->getType()
            ));
            $this->addFlash('warning', $message);
        } else {
            $executeProcessHandler->handle($executeProcessCommand);

            $message = $translator->trans(sprintf('back.tmdb.build.process.%s.start', $executeProcessCommand->getType()));
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
    public function progressAction(BuildProcessRepository $buildProcessRepository): JsonResponse
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
            'options' => $pendingProcess->getOptions(),
        ]);
    }
}
