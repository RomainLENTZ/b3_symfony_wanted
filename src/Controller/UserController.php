<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/{id}/hunts', name: 'app_user')]
    public function hunts(Request $request, UserRepository $userRepository, string $id): Response
    {

        $user = $userRepository->find($id);

        if ($user == null)
            return new Response(status: 404);

        $hunts = $user->getMyHunts();

        if (count($hunts) > 0) {
            $hunts[0]->getName();
        }

        return $this->render('user/hunts.html.twig', [
            'hunts' => $hunts,
        ]);
    }


}