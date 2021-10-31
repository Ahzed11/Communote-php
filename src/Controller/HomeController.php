<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/privacy-policy', name: 'privacy_policy')]
    public function privacyPolicy(): Response
    {
        return $this->render('home/privacy-policy.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
