<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Note;
use App\Entity\NoteFile;
use App\Form\CommentType;
use App\Form\NoteType;
use App\Repository\CommentRepository;
use App\Repository\NoteRepository;
use App\Repository\ReviewRepository;
use App\Service\S3Helper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
#[Route('/note')]
class NoteController extends BaseController
{
    /**
     * @IsGranted("ROLE_VALIDATED", message="Your account is not validated")
     */
    #[Route('/create', name: 'note_create')]
    public function write(S3Helper $uploaderHelper, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(NoteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /**
             * @var $note NoteFile
             */
            $uploadedFile = $form['noteFile']->getData();

            /**
             * @var $note Note
             */
            $note = $form->getData();

            if ($uploadedFile) {
                $newFileName = $uploaderHelper->uploadNoteFile($uploadedFile, $note->getPath(), null);
                $noteFile = new NoteFile();
                $noteFile->setFileName($newFileName);
                $noteFile->setOriginalFilename($uploadedFile->getClientOriginalName() ?? $newFileName);
                $noteFile->setMimeType($uploadedFile->getMimeType() ?? 'application/octet-stream');

                $note->setNoteFile($noteFile);

                $em->persist($note);
                $em->flush();

                return $this->redirectToRoute('note_view', ['slug' => $note->getSlug()]);
            }
        }

        return $this->render("note/write.html.twig", [
            'controller_name' => 'NoteController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("NOTE_EDIT", subject="note", message="You do not own this note")
     */
    #[Route('/edit/{slug}', name: 'note_edit')]
    public function edit(Note $note, S3Helper $uploaderHelper,
                         Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /**
             * @var $note NoteFile
             */
            $uploadedFile = $form['noteFile']->getData();

            /**
             * @var $note Note
             */
            $note = $form->getData();

            if ($uploadedFile) {
                $newFileName = $uploaderHelper->uploadNoteFile($uploadedFile, $note->getPath(), $note->getNoteFile()->getFileName());
                $noteFile = new NoteFile();
                $noteFile->setFileName($newFileName);
                $noteFile->setOriginalFilename($uploadedFile->getClientOriginalName() ?? $newFileName);
                $noteFile->setMimeType($uploadedFile->getMimeType() ?? 'application/octet-stream');

                $note->setNoteFile($noteFile);
            }

            $em->persist($note);
            $em->flush();

            return $this->redirectToRoute('note_view', ['slug' => $note->getSlug()]);
        }

        return $this->render("note/write.html.twig", [
            'controller_name' => 'NoteController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("NOTE_DELETE", subject="note", message="You do not own this note")
     */
    #[Route('/delete/{slug}', name: 'note_delete')]
    public function delete(Note $note, EntityManagerInterface $em,
                           S3Helper $uploaderHelper): Response
    {
        $uploaderHelper->deleteNoteFile($note);

        $em->remove($note);
        $em->flush();

        return $this->redirectToRoute('my_notes');
    }

    #[Route('/download/{slug}', name: 'note_download')]
    public function download(Note $note, S3Helper $s3Helper): RedirectResponse
    {
        return $s3Helper->getDownloadRedirectResponse($note);
    }

    #[Route('/view/{slug}', name: 'note_view')]
    public function view(string $slug,NoteRepository $noteRepository, CommentRepository $commentRepository,
                         ReviewRepository $reviewRepository, PaginatorInterface $paginator, Request $request,
                         EntityManagerInterface $em)
        : Response
    {
        $note = $noteRepository->getNoteBySlug($slug);
        if(!$note) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->isGranted("ROLE_VALIDATED", $this->getUser())) {
                throw $this->createAccessDeniedException("Your account is not validated");
            }
            /**
             * @var $comment Comment
             */
            $comment = $form->getData();
            $comment->setNote($note);

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('note_view', ['slug' => $note->getSlug()]);
        }

        $userReview = $reviewRepository->getByNoteSlugAndUser($note->getSlug(), $this->getUser()->getId());

        $query = $commentRepository->queryCommentsByNoteSlug($note->getSlug());
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8,
        );

        return $this->render("note/view.html.twig", [
            'controller_name' => 'NoteController',
            'note' => $note,
            'userReview' => $userReview,
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }
}
