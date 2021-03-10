<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\NoteFile;
use App\Form\NoteType;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
            "form" => $form->createView(),
        ]);
    }
}
