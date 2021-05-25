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

        // ==== Start Download Chart ====
        $downloadChart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $downloadStats = $downloadRepository->getNumberOfDownloadByDate();

        $downloadDates = [];
        $downloadCounter = [];
        foreach ($downloadStats as $stat) {
            $downloadDates[] = $stat["date"];
            $downloadCounter[] = $stat["counter"];
        }

        $downloadChart->setData([
            'labels' => $downloadDates,
            'datasets' => [
                [
                    'label' => 'Downloads',
                    'borderColor' => '#34d399',
                    'data' => $downloadCounter,
                ],
            ],
        ]);

        $downloadChart->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => max($downloadCounter)]],
                ],
            ],
        ]);
        // ==== End Download Chart ====

        // ==== Start Reviews Chart ====
        $reviewChart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $reviewStats = $reviewRepository->getReviewsGroupedByScore();

        $reviewScore = [];
        $reviewCounter = [];
        foreach ($reviewStats as $stat) {
            $reviewScore[] = $stat["score"];
            $reviewCounter[] = $stat["counter"];
        }

        $colors = [
            'rgba(255, 0, 0, 0.2)',
            'rgba(200, 50, 0, 0.2)',
            'rgba(150, 100, 0, 0.2)',
            'rgba(100, 150, 0, 0.2)',
            'rgba(50, 200, 0, 0.2)',
            'rgba(0, 255, 0, 0.2)',
        ];

        $reviewChart->setData([
            'labels' => $reviewScore,
            'datasets' => [
                [
                    'label' => 'Reviews score',
                    'borderColor' => $colors,
                    'backgroundColor' => $colors,
                    'data' => $reviewCounter,
                ],
            ],
        ]);
        // ==== End Reviews Chart ====

        // ==== Start Note Chart ====
        $noteChart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $noteStats = $noteRepository->getNumberOfDownloadByDate();

        $creationDates = [];
        $noteCounter = [];
        foreach ($noteStats as $stat) {
            $creationDates[] = $stat["date"];
            $noteCounter[] = $stat["counter"];
        }

        $noteChart->setData([
            'labels' => $creationDates,
            'datasets' => [
                [
                    'label' => 'Notes',
                    'borderColor' => '#34d399',
                    'data' => $noteCounter,
                ],
            ],
        ]);

        $noteChart->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => max($noteCounter)]],
                ],
            ],
        ]);
        // ==== End Note Chart ====

        return $this->render('admin/overview.html.twig', [
            'controller_name' => 'AdminController',
            'userCount' => $userCount,
            'noteCount' => $noteCount,
            'reportCount' => $reportCount,
            'reviewCount' => $reviewCount,
            'commentCount' => $commentCount,
            'downloadChart' => $downloadChart,
            'reviewChart' => $reviewChart,
            'noteChart' => $noteChart
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
            'entities' => 'school',
            'createButtonText' => 'Create a school',
            'targetController' => 'school'
        ]);
    }

    #[Route('/faculties', name: 'admin_faculties')]
    public function faculties(FacultyRepository $facultyRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $term = $request->query->get('search');
        $query = $term === null ? $facultyRepository->queryAlphabetically() : $facultyRepository->queryByTitle($term);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/only-title.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination,
            'entities' => 'faculty',
            'createButtonText' => 'Create a faculty',
            'targetController' => 'faculty'
        ]);
    }

    #[Route('/studies', name: 'admin_studies')]
    public function studies(StudyRepository $studyRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $term = $request->query->get('search');
        $query = $term === null ? $studyRepository->queryAlphabetically() : $studyRepository->queryByTitle($term);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/only-title.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination,
            'entities' => 'study',
            'createButtonText' => 'Create a study',
            'targetController' => 'study'
        ]);
    }

    #[Route('/courses', name: 'admin_courses')]
    public function courses(CourseRepository $courseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $term = $request->query->get('search');
        $query = $term === null ? $courseRepository->queryAlphabetically() : $courseRepository->queryByTitle($term);

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
        $term = $request->query->get('search');
        $query = $term === null ? $yearRepository->queryAlphabetically() : $yearRepository->queryByTitle($term);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/only-title.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination,
            'entities' => 'year',
            'createButtonText' => 'Create a year',
            'targetController' => 'year'
        ]);
    }

    #[Route('/notes', name: 'admin_notes')]
    public function notes(NoteRepository $noteRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $term = $request->query->get('search');
        $query = $term === null ? $noteRepository->queryByCreatedAtDesc() : $noteRepository->queryByTitle($term);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/note.html.twig', [
            'controller_name' => 'AdminController',
            'entities' => 'note',
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
            'entities' => 'download',
            'pagination' => $pagination
        ]);
    }

    #[Route('/comments', name: 'admin_comments')]
    public function comments(CommentRepository $commentRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $term = $request->query->get('search');
        $query = $term === null ? $commentRepository->queryByCreatedAtDesc()
            : $commentRepository->queryCommentsByAuthorOrNote($term);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/comment.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination,
            'entities' => 'comment',
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
        $term = $request->query->get('search');
        $query = $term === null ? $reportRepository->queryByCreatedAtDesc()
            : $reportRepository->queryReportsByAuthorOrNote($term);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->paginationLimit,
        );

        return $this->render('admin/comment.html.twig', [
            'controller_name' => 'AdminController',
            'pagination' => $pagination,
            'entities' => 'report',
        ]);
    }
}
