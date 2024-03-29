<?php

namespace App\Controller;

use App\Entity\School;
use App\Form\SchoolType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/school')]
class SchoolController extends BaseController
{
    #[Route('/create', name: 'school_create')]
    #[IsGranted("SCHOOL_CREATE")]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SchoolType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /**
             * @var $school School
             */
            $school = $form->getData();

            $em->persist($school);
            $em->flush();

            return $this->redirectToRoute('admin_schools');
        }

        return $this->render('school/create.html.twig', [
            'controller_name' => 'SchoolController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'school_edit')]
    #[IsGranted("SCHOOL_EDIT")]
    public function edit(School $school, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SchoolType::class, $school);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /**
             * @var $school School
             */
            $school = $form->getData();

            $em->persist($school);
            $em->flush();

            return $this->redirectToRoute('admin_schools');
        }

        return $this->render('school/create.html.twig', [
            'controller_name' => 'SchoolController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'school_delete')]
    #[IsGranted("SCHOOL_DELETE")]
    public function delete(School $school, EntityManagerInterface $em): Response
    {
        $em->remove($school);
        $em->flush();

        return $this->redirectToRoute('admin_schools');
    }
}
