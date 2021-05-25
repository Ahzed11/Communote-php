<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Note;
use App\Entity\Report;
use App\Form\ReportType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
#[Route('/report')]
class ReportController extends BaseController
{
    /**
     * @IsGranted("ROLE_VALIDATED", message="Your account is not validated")
     */
    #[Route('/create/{slug}', name: 'report_create')]
    public function create(Note $note, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(ReportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var $report Report
             */
            $report = $form->getData();

            $report->setNote($note);

            $em->persist($report);
            $em->flush();

            return $this->redirectToRoute('note_view', ['slug' => $note->getSlug()]);
        }

        return $this->render("report/create.html.twig", [
            'controller_name' => 'ReportController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("REPORT_CREATE", subject="report", message="You are not allowed to delete this report")
     */
    #[Route('/delete/{id}', name: 'report_delete')]
    public function delete(Report $report, EntityManagerInterface $em, Request $request): Response
    {
        $em->remove($report);
        $em->flush();

        return $this->redirectToRoute('admin_reports');
    }
}
