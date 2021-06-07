<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\DownloadRepository;
use App\Repository\NoteRepository;
use App\Repository\ReviewRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/profile')]
class ProfileController extends BaseController
{
    #[Route('/user/{id}', name: 'profile')]
    public function index(User $user, NoteRepository $noteRepository, PaginatorInterface $paginator,
                            ReviewRepository $reviewRepository, Request $request): Response
    {
        $reviewAverage = $reviewRepository->getAverageOfNotesByUserId($user->getId());

        $query = $noteRepository->queryByUser($user->getUsername());

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8,
        );

        return $this->render('profile/profile.html.twig', [
            'controller_name' => 'MeController',
            'pagination' => $pagination,
            'reviewAverage' => $reviewAverage,
            'numberOfCreatedNotes' => $pagination->getTotalItemCount()
        ]);
    }

    #[Route('/me', name: 'profile_me')]
    public function me(NoteRepository $noteRepository, DownloadRepository $downloadRepository,
                              ReviewRepository $reviewRepository, PaginatorInterface $paginator,
                              Request $request): Response
    {
        return $this->index($this->getUser(), $noteRepository, $paginator, $reviewRepository, $request);
    }
}
