<?php

namespace App\Controller;

use App\Entity\Faculty;
use App\Repository\CourseRepository;
use App\Repository\FacultyRepository;
use App\Repository\NoteRepository;
use App\Repository\StudyRepository;
use App\Repository\YearRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

const BROWSE_TEMPLATE = "browse/browse.html.twig";
const SIMPLE_CARD_COMPONENT = "component/card/card-simple.html.twig";
const COURSE_CARD_COMPONENT = "component/card/card-course.html.twig";
const NOTE_CARD_COMPONENT = "component/card/card-note.html.twig";
const PAGINATION_LIMIT = 20;


/**
 * @IsGranted("ROLE_USER")
 */
#[Route('/browse')]
class BrowseController extends BaseController
{
    #[Route('/', name: 'browse_faculty')]
    public function faculty(FacultyRepository $facultyRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $term = $request->query->get('search');
        $query = $facultyRepository->queryByTitle($term);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            PAGINATION_LIMIT,
        );

        return $this->render(BROWSE_TEMPLATE, [
            'controller_name' => 'BrowseController',
            'title' => 'Faculties',
            'pagination' => $pagination,
            'cardComponent' => SIMPLE_CARD_COMPONENT,
        ]);
    }

    #[Route('/{faculty}', name: 'browse_study')]
    public function study(string $faculty, StudyRepository $studyRepository, PaginatorInterface $paginator,
                          Request $request): Response
    {
        $term = $request->query->get('search');
        $query = $studyRepository->queryByTitleAndFaculty($term, $faculty);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            PAGINATION_LIMIT,
        );

        return $this->render(BROWSE_TEMPLATE, [
            'controller_name' => 'BrowseController',
            'title' => 'Studies',
            'pagination' => $pagination,
            'cardComponent' => SIMPLE_CARD_COMPONENT,
        ]);
    }

    #[Route('/{faculty}/{study}', name: 'browse_year')]
    public function year(YearRepository $yearRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $term = $request->query->get('search');
        $query = $yearRepository->queryByTitle($term);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            PAGINATION_LIMIT,
        );

        return $this->render(BROWSE_TEMPLATE, [
            'controller_name' => 'BrowseController',
            'title' => 'Years',
            'pagination' => $pagination,
            'cardComponent' => SIMPLE_CARD_COMPONENT,
        ]);
    }

    #[Route('/{faculty}/{study}/{year}', name: 'browse_course')]
    public function course(string $faculty, string $study, string $year, CourseRepository $courseRepository,
                           PaginatorInterface $paginator, Request $request): Response
    {
        $term = $request->query->get('search');
        $query = $courseRepository->queryByTitleStudyAndYear($term, $study, $year);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            PAGINATION_LIMIT,
        );

        return $this->render(BROWSE_TEMPLATE, [
            'controller_name' => 'BrowseController',
            'title' => 'Courses',
            'pagination' => $pagination,
            'isDeletable' => false,
            'cardComponent' => COURSE_CARD_COMPONENT,
        ]);
    }

    #[Route('/{faculty}/{study}/{year}/{course}', name: 'browse_note')]
    public function note(string $faculty, string $study, string $year, string $course, NoteRepository $noteRepository,
                         PaginatorInterface $paginator, Request $request): Response
    {
        $term = $request->query->get('search');
        $query = $noteRepository->queryByTitleAndCourse($term, $course);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            PAGINATION_LIMIT,
        );

        return $this->render(BROWSE_TEMPLATE, [
            'controller_name' => 'BrowseController',
            'title' => 'Notes',
            'pagination' => $pagination,
            'cardComponent' => NOTE_CARD_COMPONENT,
            'isNote' => true,
        ]);
    }
}
