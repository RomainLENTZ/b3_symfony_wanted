<?php

namespace App\Controller;

use App\Repository\HuntRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/hunt', name: 'app_hunt')]
class HuntController extends AbstractController
{
    #[Route('/', name: '_index_hunt')]
    public function index(HuntRepository $huntRepository): Response
    {
        $hunts = $huntRepository->findAll();
        return $this->render('hunt/index.html.twig', ['controller_name' => 'HuntController','hunts'=>$hunts]);
    }

    #[Route('/show', name: '_show')]
    public function show(HuntRepository $huntRepository): Response{
        dd($huntRepository->findAll());
        return new Response();
    }
}
