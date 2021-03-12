<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\NoteFile;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use App\Service\S3Helper;
use Aws\S3\S3Client;
use Aws\S3\S3ClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/note')]
class NoteController extends BaseController
{
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

    #[Route('/delete/{slug}', name: 'note_delete')]
    public function delete(Note $note, EntityManagerInterface $em,
                           S3Helper $uploaderHelper): Response
    {
        $uploaderHelper->deleteNoteFile($note);

        $em->remove($note);
        $em->flush();

        // TODO: Redirect to user's note page

        return $this->render("home/index.html.twig", [
            'controller_name' => 'NoteController',
            'note' => $note,
        ]);
    }

    #[Route('/download/{slug}', name: 'note_download')]
    public function download(Note $note, S3Helper $s3Helper): RedirectResponse
    {
        return $s3Helper->getDownloadRedirectResponse($note);
    }

    #[Route('/view/{slug}', name: 'note_view')]
    public function view(Note $note): Response
    {
        if(!$note) {
            throw $this->createNotFoundException();
        }

        return $this->render("note/view.html.twig", [
            'controller_name' => 'NoteController',
            'note' => $note,
        ]);
    }
}
