<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use App\Repository\FacultyRepository;
use App\Repository\NoteRepository;
use App\Repository\StudyRepository;
use App\Repository\YearRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

const BROWSE_TEMPLATE = "browse/browse.html.twig";
const SIMPLE_CARD_COMPONENT = "component/card/card-simple.html.twig";
const COURSE_CARD_COMPONENT = "component/card/card-course.html.twig";
const NOTE_CARD_COMPONENT = "component/card/card-note.html.twig";


/**
 * @IsGranted("ROLE_USER")
 */
#[Route('/browse')]
class BrowseController extends AbstractController
{
    #[Route('/', name: 'browse_faculty')]
    public function faculty(FacultyRepository $facultyRepository): Response
    {
        $faculties = $facultyRepository->findAll();

        return $this->render(BROWSE_TEMPLATE, [
            'controller_name' => 'BrowseController',
            'title' => 'Faculties',
            'items' => $faculties,
            'cardComponent' => SIMPLE_CARD_COMPONENT,
        ]);
    }

    #[Route('/{faculty}', name: 'browse_study')]
    public function study(StudyRepository $studyRepository): Response
    {
        $studies = $studyRepository->findAll();

        return $this->render(BROWSE_TEMPLATE, [
            'controller_name' => 'BrowseController',
            'title' => 'Studies',
            'items' => $studies,
            'cardComponent' => SIMPLE_CARD_COMPONENT,
        ]);
    }

    #[Route('/{faculty}/{study}', name: 'browse_year')]
    public function year(YearRepository $yearRepository): Response
    {
        $years = $yearRepository->findAll();

        return $this->render(BROWSE_TEMPLATE, [
            'controller_name' => 'BrowseController',
            'title' => 'Years',
            'items' => $years,
            'cardComponent' => SIMPLE_CARD_COMPONENT,
        ]);
    }

    #[Route('/{faculty}/{study}/{year}', name: 'browse_course')]
    public function course(CourseRepository $courseRepository): Response
    {
        $courses = $courseRepository->findAll();

        return $this->render(BROWSE_TEMPLATE, [
            'controller_name' => 'BrowseController',
            'title' => 'Courses',
            'items' => $courses,
            'isDeletable' => false,
            'cardComponent' => COURSE_CARD_COMPONENT,
        ]);
    }

    #[Route('/{faculty}/{study}/{year}/{course}', name: 'browse_note')]
    public function note(NoteRepository $noteRepository): Response
    {
        $notes = $noteRepository->findAll();

        return $this->render(BROWSE_TEMPLATE, [
            'controller_name' => 'BrowseController',
            'title' => 'Notes',
            'items' => $notes,
            'cardComponent' => NOTE_CARD_COMPONENT,
        ]);
    }
}
