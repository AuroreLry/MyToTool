<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Entity\Task;
use App\Repository\ListeRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'create_task', methods: ['POST'])]
    public function create(Request $request, ManagerRegistry $registry, Session $session, ListeRepository $listeRepository): JsonResponse
    {
        if(!$request->request->get('nom')) {
            return new JsonResponse(['error' => 'Veuillez renseigner un nom'], Response::HTTP_BAD_REQUEST);
        }

        $task = new Task();
        $task->setName($request->request->get('nom'));
        $task->setPriority($request->request->get('priority'));
        $task->setListe($listeRepository->find($request->request->get('liste')));

        $entityManager = $registry->getManager();
        $entityManager->persist($task);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Task created'], Response::HTTP_CREATED);
    }

    #[Route('/task', name: 'supprimer_tache', methods: ['DELETE'])]
    public function delete(Request $request, ManagerRegistry $registry, Session $session, TaskRepository $taskRepository): JsonResponse
    {
        if(!$request->request->get('taskId')) {
            return new JsonResponse(['error' => 'Veuillez renseigner une tache'], Response::HTTP_BAD_REQUEST);
        }

        $task = $taskRepository->find($request->request->get('taskId'));

        $entityManager = $registry->getManager();
        $entityManager->remove($task);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Tâche supprimée'], Response::HTTP_CREATED);

    }

    #[Route('/task', name: 'patch_tache', methods: ['PATCH'])]
    public function patch(Request $request, ManagerRegistry $registry, Session $session, TaskRepository $taskRepository): JsonResponse
    {
        $task = $taskRepository->find($request->request->get('taskId'));


        $task->setActive(!$task->getActive());

        $entityManager = $registry->getManager();
        $entityManager->persist($task);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Tâche mise à jour'], Response::HTTP_CREATED);

    }

}
