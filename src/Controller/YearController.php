<?php

namespace App\Controller;

use App\Entity\Year;
use App\Form\YearType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted("ROLE_ADMIN")]
#[Route('/year')]
class YearController extends BaseController
{
    #[Route('/create', name: 'year_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(YearType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /**
             * @var $year Year
             */
            $year = $form->getData();

            $em->persist($year);
            $em->flush();

            return $this->redirectToRoute('admin_years');
        }

        return $this->render('year/create.html.twig', [
            'controller_name' => 'StudyController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'year_edit')]
    public function edit(Year $year, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(YearType::class, $year);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /**
             * @var $year Year
             */
            $year = $form->getData();

            $em->persist($year);
            $em->flush();

            return $this->redirectToRoute('admin_years');
        }

        return $this->render('year/create.html.twig', [
            'controller_name' => 'StudyController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'year_delete')]
    public function delete(Year $year, EntityManagerInterface $em): Response
    {
        $em->remove($year);
        $em->flush();

        return $this->redirectToRoute('admin_years');
    }
}
