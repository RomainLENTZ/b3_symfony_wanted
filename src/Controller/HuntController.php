<?php

namespace App\Controller;

use App\Entity\Hunt;
use App\Form\HuntType;
use App\Repository\HuntRepository;
use App\Repository\TargetRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/hunt', name: 'app_hunt')]
class HuntController extends AbstractController
{
    #[Route('/', name: '_index_hunt')]
    public function index(Request $request, HuntRepository $huntRepository): Response
    {
        $hunts = $huntRepository->findAll();
        $me = $this->getUser();

        $myHunts = array_map(function (Hunt $hunt) use ($me) {
            $hunters = $hunt->getHunters();
            $hasHunt = $hunters->contains($me);
            return $hasHunt;
        }, $hunts);

        // dd($myHunts);


        return $this->render('hunt/index.html.twig', ['hunts' => $hunts, 'myHunts' => $myHunts]);
    }

    #[Route('/{id}', name: '_hunt')]
    public function hunt(HuntRepository $huntRepository, string $id): Response
    {
        $hunt = $huntRepository->find($id);

        if ($hunt == null)
            return new Response(status: 404);

        return $this->render('hunt/hunt.html.twig', ['hunt' => $hunt]);
    }

    #[Route('/add/hunt', name: '_form_hunt', methods: ["GET"])]
    public function formHunt(Request $request): Response
    {
        if (!$this->isGranted('add', $this->getUser())) {
            return $this->redirectToRoute('app_access_denied');
        }

        if ($request->getSession()->getFlashBag()->peek('targetId', array()) == []) {
            return $this->redirectToRoute('app_target_index_target');
        }

        $huntExemple = new Hunt();
        $huntExemple->setName("")
            ->setBounty(0);

        $form = $this->createForm(HuntType::class, $huntExemple);
        return $this->render('hunt/form.html.twig', ['form' => $form->createView()]);
    }


    #[Route('/add/save', name: '_add_hunt', methods: ["POST"])]
    public function add(Request $request, EntityManagerInterface $entityManager, TargetRepository $targetRepository): Response
    {
        $hunt = new Hunt();
        $concernedTargetId = $request->getSession()->getFlashBag()->get('targetId');
        $hunt->setTarget($targetRepository->find($concernedTargetId[0]));
        $hunt->setAuthor($this->getUser());
        $huntForm = $this->createForm(HuntType::class, $hunt);
        $huntForm->handleRequest($request);

        if ($huntForm->isSubmitted() && $huntForm->isValid()) {
            $hunt = $huntForm->getData();
            $entityManager->persist($hunt);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_hunt_index_hunt');
    }

    #[Route('/user-get-hunt/{id}', name: '_user_get_hunt')]
    public function getHunt(Request $request, HuntRepository $huntRepository, string $id): Response
    {
        $hunt = $huntRepository->find($id);

        $hunt->addHunter($this->getUser());

        $huntRepository->save($hunt, true);

        return $this->redirectToRoute('app_hunt_index_hunt');
    }

    #[Route('/{id}/close', name: '_close_hunt')]
    public function closeHuntForm(EntityManagerInterface $entityManager,UserRepository $userRepository ,HuntRepository $huntRepository, string $id): Response
    {
        if (!$this->isGranted('close', $this->getUser())) {
            return $this->redirectToRoute('app_access_denied');
        }

        $theHunt = $huntRepository->find($id);
        $huntersId = $huntRepository->findHuntHunters($theHunt->getId());

        $hunters=[];
        foreach ($huntersId as $hunterId){
            $hunters[] = $userRepository->find($hunterId["id"]);
        }

        return $this->render('hunt/close.html.twig', ['hunt'=>$theHunt ,'hunters' => $hunters]);
    }

    #[Route('/close/proceed', name: '_close_hunt_proceed')]
    public function closeHunt(Request $request ,EntityManagerInterface $entityManager,UserRepository $userRepository ,HuntRepository $huntRepository): Response
    {
        $id = $request->request->get('huntId');
        if (!$this->isGranted('close', $this->getUser())) {
            return $this->redirectToRoute('app_access_denied');
        }

        $huntToClose = $huntRepository->find($id);

        if ($request->request->get('hunters') != -1){
            $hunterToPay = $userRepository->find($request->request->get('hunters'));
            $hunterToPayWallet = $hunterToPay->getWallet();
            $hunterToPayWallet->setAmount($hunterToPayWallet->getAmount() + $huntToClose->getBounty());
            $entityManager->persist($hunterToPayWallet);
            $entityManager->persist($hunterToPay);
        }

        $huntToClose->setIsOpen(false);
        $entityManager->persist($huntToClose);
        $entityManager->flush();

        return $this->render('hunt/closeRecap.html.twig', ['hunt'=>$huntToClose]);
    }


}