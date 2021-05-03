<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/me')]
class MeController extends AbstractController
{
    #[Route('/my-notes', name: 'my_notes')]
    public function myNotes(NoteRepository $noteRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $noteRepository->queryByUser($this->getUser()->getUsername());

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8,
        );

        return $this->render('me/my-notes.html.twig', [
            'controller_name' => 'MeController',
            'pagination' => $pagination,
        ]);
    }

    #[Route('/my-account', name: 'my_account')]
    public function myAccount(): Response
    {
        return $this->render('me/my-account.html.twig', [
            'controller_name' => 'MeController',
        ]);
    }
}
