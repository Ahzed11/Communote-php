<?php

namespace App\Controller;

use App\Entity\Study;
use App\Form\StudyType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted("ROLE_ADMIN")]
#[Route('/study')]
class StudyController extends BaseController
{
    #[Route('/create', name: 'study_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(StudyType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /**
             * @var $study Study
             */
            $study = $form->getData();

            $em->persist($study);
            $em->flush();

            return $this->redirectToRoute('admin_studies');
        }

        return $this->render('study/create.html.twig', [
            'controller_name' => 'StudyController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'study_edit')]
    public function edit(Study $study, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(StudyType::class, $study);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /**
             * @var $study Study
             */
            $study = $form->getData();

            $em->persist($study);
            $em->flush();

            return $this->redirectToRoute('admin_studies');
        }

        return $this->render('study/create.html.twig', [
            'controller_name' => 'StudyController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'study_delete')]
    public function delete(Study $study, EntityManagerInterface $em): Response
    {
        $em->remove($study);
        $em->flush();

        return $this->redirectToRoute('admin_studies');
    }
}
