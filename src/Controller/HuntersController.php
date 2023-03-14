<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/hunters', name: 'app_hunters')]
class HuntersController extends AbstractController
{
    #[Route('/', name: '_index_hunters')]
    public function index(Request $request, UserRepository $userRepository): Response
    {

        $hunters = $userRepository->findAll();


        return $this->render('hunters/index.html.twig', [
            'hunters' => $hunters,
        ]);
    }


    #[Route('/addTeammate/{id}', name: '_add_teammate')]
    public function addTeammate(Request $request, UserRepository $userRepository, string $id): Response
    {

        $currentUser = $this->getUser();

        if ($currentUser == null)
            return $this->redirectToRoute('app_login');

        $user = $userRepository->find($currentUser->getId());

        $newTeammate = $userRepository->find($id);

        $user->addTeammate($newTeammate);
        $newTeammate->addTeammate($user);

        $userRepository->save($user, true);
        $userRepository->save($newTeammate, true);

        return $this->redirectToRoute('app_hunters_index_hunters');

    }


}