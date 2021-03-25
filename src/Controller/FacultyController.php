<?php

namespace App\Controller;

use App\Entity\Faculty;
use App\Form\FacultyType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/faculty')]
/**
 * @IsGranted("ROLE_ADMIN")
 */
class FacultyController extends BaseController
{
    #[Route('/create', name: 'faculty_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FacultyType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /**
             * @var $faculty Faculty
             */
            $faculty = $form->getData();

            $em->persist($faculty);
            $em->flush();

            return $this->redirectToRoute('admin_faculties');
        }

        return $this->render('school/create.html.twig', [
            'controller_name' => 'FacultyController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'faculty_edit')]
    public function edit(Faculty $faculty, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FacultyType::class, $faculty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /**
             * @var $faculty Faculty
             */
            $faculty = $form->getData();

            $em->persist($faculty);
            $em->flush();

            return $this->redirectToRoute('admin_faculties');
        }

        return $this->render('school/create.html.twig', [
            'controller_name' => 'FacultyController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'faculty_delete')]
    public function delete(Faculty $faculty, EntityManagerInterface $em): Response
    {
        $em->remove($faculty);
        $em->flush();

        return $this->redirectToRoute('admin_faculties');
    }
}
