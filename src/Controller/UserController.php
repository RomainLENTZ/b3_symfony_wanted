<?php

namespace App\Controller;

use App\Form\EditProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

#[Route('/user', name: 'app_user')]
class UserController extends AbstractController
{
    #[Route('/', name: '_index_user')]
    public function index(): Response
    {
        if($this->getUser() != null) {
            $editForm = $this->createForm(EditProfileType::class, $this->getUser());
            return $this->render('user/index.html.twig', [ 'form' => $editForm->createView()
            ]);
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/edit', name: '_edit')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher ): Response
    {
        if($this->getUser() != null) {
            $userPassword = $this->getUser()->getPassword();
            $user = $this->getUser();
            $editForm = $this->createForm(EditProfileType::class, $this->getUser());
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                if (!empty($editForm->getData()->getPassword())) {
                    $encodedPassword = $userPasswordHasher->hashPassword($this->getUser(), $editForm->getData()->getPassword());
                    $user->setPassword($encodedPassword);
                } else {
                    $user->setPassword($userPassword);
                }

                $entityManager->persist($user);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_hunt_index_hunt');
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/hunts', name: '_hunts')]
    public function hunts(Request $request, UserRepository $userRepository): Response
    {
        $currentUser = $this->getUser();

        if ($currentUser == null)
            return $this->redirectToRoute('app_login');

        $user = $userRepository->find($currentUser->getId());
        $hunts = $user->getMyHunts();

        if (count($hunts) > 0) {
            $hunts[0]->getName();
        }

        return $this->render('user/hunts.html.twig', [
            'hunts' => $hunts,
        ]);
    }
}