<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/course')]
class CourseController extends BaseController
{
    #[Route('/create', name: 'course_create')]
    #[IsGranted("COURSE_CREATE")]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CourseType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /**
             * @var $course Course
             */
            $course = $form->getData();

            $em->persist($course);
            $em->flush();

            return $this->redirectToRoute('admin_courses');
        }

        return $this->render('course/create.html.twig', [
            'controller_name' => 'CourseController',
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted("COURSE_EDIT")]
    #[Route('/edit/{id}', name: 'course_edit')]
    public function edit(Course $course, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /**
             * @var $course Course
             */
            $course = $form->getData();

            $em->persist($course);
            $em->flush();

            return $this->redirectToRoute('admin_courses');
        }

        return $this->render('course/create.html.twig', [
            'controller_name' => 'CourseController',
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted("COURSE_DELETE")]
    #[Route('/delete/{id}', name: 'course_delete')]
    public function delete(Course $course, EntityManagerInterface $em): Response
    {
        $em->remove($course);
        $em->flush();

        return $this->redirectToRoute('admin_courses');
    }

    #[IsGranted("ROLE_VALIDATED")]
    #[Route('/api/', name: 'course_get_api', methods: ['GET'])]
    public function getApi(CourseRepository $courseRepository, Request $request): Response
    {
        $term = $request->query->get('term') ?? '';
        $courses = $courseRepository->getByTitleOrCode($term);

        return $this->json($courses, 200, [], [
            'groups' => 'course:read'
        ]);
    }
}
