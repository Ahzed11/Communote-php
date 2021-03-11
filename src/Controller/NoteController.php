<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\NoteFile;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/note')]
class NoteController extends BaseController
{
    #[Route('/write', name: 'note_write')]
    public function write(UploaderHelper $uploaderHelper, Request $request, EntityManagerInterface $em): Response
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

                // TODO: Redirect to user's note page
            }
        }

        return $this->render("note/write.html.twig", [
            'controller_name' => 'NoteController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{slug}', name: 'note_delete')]
    public function delete(string $slug, NoteRepository $noteRepository, EntityManagerInterface $em,
                           UploaderHelper $uploaderHelper): Response
    {
        $note = $noteRepository->findOneBy(['slug'=>$slug]);

        $uploaderHelper->deleteFile($uploaderHelper->getNoteFilePublicPath($note));

        $em->remove($note);
        $em->flush();

        // TODO: Redirect to user's note page

        return $this->render("home/index.html.twig", [
            'controller_name' => 'NoteController',
            'note' => $note,
        ]);
    }

    #[Route('/view/{slug}', name: 'note_view')]
    public function view(string $slug, NoteRepository $noteRepository): Response
    {
        $note = $noteRepository->getNoteBySlug($slug)[0];

        if(!$note) {
            throw $this->createNotFoundException();
        }

        return $this->render("note/view.html.twig", [
            'controller_name' => 'NoteController',
            'note' => $note,
        ]);
    }
}
