<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

/**
 * @IsGranted("ROLE_USER")
 */
#[Route('/comment')]
class CommentController extends BaseController
{
    /**
     * @IsGranted("COMMENT_DELETE", subject="comment", message="You are not allowed to delete this message")
     */
    #[Route('/delete/{id}', name: 'comment_delete')]
    public function delete(Comment $comment, EntityManagerInterface $em, Request $request): Response
    {
        $slug = $comment->getNote()->getSlug();

        $em->remove($comment);
        $em->flush();

        $referer = $request->headers->get('referer');
        if (strpos($referer, "admin")) {
            return $this->redirectToRoute('admin_comments');
        }

        return $this->redirectToRoute('note_view', ['slug' => $slug]);
    }
}
