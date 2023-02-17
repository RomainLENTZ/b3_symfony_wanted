<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HuntController extends AbstractController
{
    #[Route('/hunt', name: 'app_hunt')]
    public function index(): Response
    {
        return $this->render('hunt/index.html.twig', [
            'controller_name' => 'HuntController',
        ]);
    }
}
