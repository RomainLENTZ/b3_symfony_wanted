<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TargetController extends AbstractController
{
    #[Route('/target', name: 'app_target')]
    public function index(): Response
    {
        return $this->render('target/index.html.twig', [
            'controller_name' => 'TargetController',
        ]);
    }
}
