<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\CourseRepository;
use App\Repository\FacultyRepository;
use App\Repository\NoteRepository;
use App\Repository\ReportRepository;
use App\Repository\ReviewRepository;
use App\Repository\SchoolRepository;
use App\Repository\StudyRepository;
use App\Repository\UserRepository;
use App\Repository\YearRepository;
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
    public function overview(UserRepository $userRepository, NoteRepository $noteRepository, ReportRepository $reportRepository,
                            ReviewRepository $reviewRepository, CommentRepository $commentRepository): Response
    {
        $userCount = $userRepository->count([]);
        $noteCount = $noteRepository->count([]);
        $reportCount = $reportRepository->count([]);
        $reviewCount = $reviewRepository->count([]);
        $commentCount = $commentRepository->count([]);

        return $this->render('admin/overview.html.twig', [
            'controller_name' => 'AdminController',
            'userCount' => $userCount,
            'noteCount' => $noteCount,
            'reportCount' => $reportCount,
            'reviewCount' => $reviewCount,
            'commentCount' => $commentCount,
        ]);
    }

    #[Route('/schools', name: 'admin_schools')]
    public function schools(SchoolRepository $schoolRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $schoolRepository->queryAlphabetically();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/only-title.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination,
            'entities' => 'Schools',
            'createButtonText' => 'Create a school',
            'createButtonPath' => 'school_create'
        ]);
    }

    #[Route('/faculties', name: 'admin_faculties')]
    public function faculties(FacultyRepository $facultyRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $facultyRepository->queryAlphabetically();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/only-title.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination,
            'entities' => 'Faculties',
            'createButtonText' => 'Create a faculty',
            'createButtonPath' => 'faculty_create'
        ]);
    }

    #[Route('/studies', name: 'admin_studies')]
    public function studies(StudyRepository $studyRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $studyRepository->queryAlphabetically();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/only-title.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination,
            'entities' => 'Studies',
            'createButtonText' => 'Create a study',
            'createButtonPath' => 'study_create'
        ]);
    }

    #[Route('/courses', name: 'admin_courses')]
    public function courses(CourseRepository $courseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $courseRepository->queryAlphabetically();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/course.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination
        ]);
    }

    #[Route('/years', name: 'admin_years')]
    public function years(YearRepository $yearRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $yearRepository->queryAlphabetically();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/only-title.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination,
            'entities' => 'Years',
            'createButtonText' => 'Create a year',
            'createButtonPath' => 'year_create'
        ]);
    }

    #[Route('/notes', name: 'admin_notes')]
    public function notes(NoteRepository $noteRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $noteRepository->queryByCreatedAtDesc();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/note.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination
        ]);
    }

    #[Route('/comments', name: 'admin_comments')]
    public function comments(CommentRepository $commentRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $commentRepository->queryByCreatedAtDesc();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/comment.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination
        ]);
    }

    #[Route('/reviews', name: 'admin_reviews')]
    public function reviews(ReviewRepository $reviewRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $reviewRepository->queryByCreatedAtDesc();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/review.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination
        ]);
    }

    #[Route('/reports', name: 'admin_reports')]
    public function reports(ReportRepository $reportRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $reportRepository->queryByCreatedAtDesc();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/comment.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination
        ]);
    }
}