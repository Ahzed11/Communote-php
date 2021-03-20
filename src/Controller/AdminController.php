<?php

namespace App\Controller;

use App\Repository\SchoolRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
#[Route('/admin')]
class AdminController extends AbstractController
{
    private int $paginationLimit = 20;

    #[Route('/overview', name: 'admin_overview')]
    public function overview(): Response
    {
        return $this->render('admin/overview.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/schools', name: 'admin_schools')]
    public function schools(SchoolRepository $schoolRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $schoolRepository->querySchoolsAlphabetically();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/schools.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination
        ]);
    }

    #[Route('/faculties', name: 'admin_faculties')]
    public function faculties(): Response
    {
        return $this->render('admin/overview.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/studies', name: 'admin_studies')]
    public function studies(): Response
    {
        return $this->render('admin/overview.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/courses', name: 'admin_courses')]
    public function courses(): Response
    {
        return $this->render('admin/overview.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/years', name: 'admin_years')]
    public function years(): Response
    {
        return $this->render('admin/overview.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/notes', name: 'admin_notes')]
    public function notes(): Response
    {
        return $this->render('admin/overview.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
