<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Review;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/review')]
class ReviewController extends BaseController
{
    #[IsGranted("REVIEW_CREATE", message: "Your account is not validated")]
    #[Route('/create/{slug}', name: 'review_create')]
    public function create(Note $note, EntityManagerInterface $em, Request $request, ReviewRepository $reviewRepository): Response
    {
        $score = $request->query->get('score');
        if (!$score) {
            return new Response("Score is missing", 400);
        }

        $review = $reviewRepository->getByNoteSlugAndUser($note->getSlug(), $this->getUser()->getId());

        if ($review === null) {
            $review = new Review();
            $review->setNote($note);
        }

        $review->setScore($score);

        $em->persist($review);
        $em->flush();

        return new Response("ok", 200);
    }

    #[IsGranted("REVIEW_DELETE", message: "You are not allowed to delete this review")]
    #[Route('/delete/{id}', name: 'review_delete')]
    public function delete(Review $review, EntityManagerInterface $em): Response
    {
        $em->remove($review);
        $em->flush();

        return $this->redirectToRoute('admin_reviews');
    }
}
