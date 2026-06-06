<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'task_list')]
    public function index(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findAll()
        ]);
    }

    #[Route('/task/new', name: 'task_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $task = new Task();
            $task->setTitle($request->request->get('title'));
            $task->setDescription($request->request->get('description'));
            $task->setStatus('pending');

            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/new.html.twig');
    }

    #[Route('/task/edit/{id}', name: 'task_edit')]
    public function edit(Task $task, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $task->setTitle($request->request->get('title'));
            $task->setDescription($request->request->get('description'));
            $task->setStatus($request->request->get('status'));

            $em->flush();

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task
        ]);
    }

    #[Route('/task/delete/{id}', name: 'task_delete')]
    public function delete(Task $task, EntityManagerInterface $em): Response
    {
        $em->remove($task);
        $em->flush();

        return $this->redirectToRoute('task_list');
    }
}