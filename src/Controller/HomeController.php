<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Repository\ListeRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(?string $listId, Session $session, ListeRepository $listeRepository): Response
    {

        if (!$session->get('user')) {
            return $this->redirectToRoute('app_login');
        }

        $userList = $listeRepository->findBy(['user' => $session->get('user')]);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $session->get('user'),
            'listes' => $userList,
        ]);
    }

    #[Route('/list', name: 'create_list', methods: ['POST'])]
    public function create(Request $request, ManagerRegistry $registry, Session $session, UserRepository $userRepository): JsonResponse
    {
        if(!$request->request->get('nom')) {
            return new JsonResponse(['error' => 'Veuillez renseigner un nom'], Response::HTTP_BAD_REQUEST);
        }

        $list = new Liste();
        $list->setNom($request->request->get('nom'));
        $list->setUser($userRepository->find($session->get('user')->getId()));

        $entityManager = $registry->getManager();
        $entityManager->persist($list);
        $entityManager->flush();

        return new JsonResponse(['message' => 'List created'], Response::HTTP_CREATED);
    }
}
