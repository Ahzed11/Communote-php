<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
#[Route('/review')]
class ReviewController extends BaseController
{
    /**
     * @IsGranted("REVIEW_CREATE")
     */
    #[Route('/create/{slug}', name: 'review_create')]
    public function create(Note $note, EntityManagerInterface $em, Request $request): Response
    {
        $score = $request->query->get('score');
        if (!$score) {
            return new Response("Score is missing", 400);
        }

        $review = new Review();
        $review->setNote($note);
        $review->setScore($score);

        $em->persist($review);
        $em->flush();

        return new Response("ok", 200);
    }

    /**
     * @IsGranted("REVIEW_EDIT")
     */
    #[Route('/edit/{id}', name: 'review_edit')]
    public function edit(Review $review, EntityManagerInterface $em, Request $request): Response
    {
        $score = $request->query->get('score');
        if (!$score) {
            return new Response("Score is missing", 400);
        }

        $review->setScore($score);

        $em->persist($review);
        $em->flush();

        return new Response("ok", 200);
    }
}
