<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\CourseRepository;
use App\Repository\DownloadRepository;
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
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

/**
 * @IsGranted("ROLE_ADMIN")
 */
#[Route('/admin')]
class AdminController extends AbstractController
{
    private int $paginationLimit = 14;

    #[Route('/overview', name: 'admin_overview')]
    public function overview(UserRepository $userRepository, NoteRepository $noteRepository, ReportRepository $reportRepository,
                            ReviewRepository $reviewRepository, CommentRepository $commentRepository,
                             DownloadRepository $downloadRepository, ChartBuilderInterface $chartBuilder): Response
    {
        $userCount = $userRepository->count([]);
        $noteCount = $noteRepository->count([]);
        $reportCount = $reportRepository->count([]);
        $reviewCount = $reviewRepository->count([]);
        $commentCount = $commentRepository->count([]);

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $downloadStats = $downloadRepository->getNumberOfDownloadByDate();

        $dates = [];
        $scores = [];
        foreach ($downloadStats as $stat) {
            $dates[] = $stat["date"];
            $scores[] = $stat["counter"];
        }

        $chart->setData([
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Downloads',
                    'borderColor' => '#34d399',
                    'data' => $scores,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => max($scores)]],
                ],
            ],
        ]);

        return $this->render('admin/overview.html.twig', [
            'controller_name' => 'AdminController',
            'userCount' => $userCount,
            'noteCount' => $noteCount,
            'reportCount' => $reportCount,
            'reviewCount' => $reviewCount,
            'commentCount' => $commentCount,
            'chart' => $chart,
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
            'targetController' => 'school'
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
            'targetController' => 'faculty'
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
            'targetController' => 'study'
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
            'targetController' => 'year'
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
            'entities' => 'Notes',
            'pagination' => $pagination
        ]);
    }

    #[Route('/downloads', name: 'admin_downloads')]
    public function downloads(DownloadRepository $downloadRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $downloadRepository->queryByCreatedAtDesc();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/download.html.twig', [
            'controller_name' => 'AdminController',
            'entities' => 'Downloads',
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
            'pagination' => $pagination,
            'entities' => 'Reports',
        ]);
    }
}
