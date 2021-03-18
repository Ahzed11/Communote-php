<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
#[Route('/course')]
class CourseController extends BaseController
{
    /**
     * @IsGranted("ROLE_VALIDATED", message="You are not allowed to delete this message")
     */
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
