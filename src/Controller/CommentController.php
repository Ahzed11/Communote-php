<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends BaseController
{
    #[Route('/delete/{id}', name: 'comment_delete')]
    public function delete(Comment $comment, EntityManagerInterface $em): Response
    {
        $slug = $comment->getNote()->getSlug();

        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('note_view', ['slug' => $slug]);
    }
}
