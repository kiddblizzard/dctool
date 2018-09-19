<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Task;


class TaskController extends Controller
{
    /**
     * [index description]
     * @Route("/tasks", name="tasks")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function getIndexAction(Request $request)
    {
        $pageNumber = $request->query->get('page', 1);
        $keyWord = $request->query->get('keyWord', null);

        $query = $this->getRepository()->findByKeyword($keyWord);

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $pageNumber,
            10
        );

        return $this->render('task/index.html.twig', array(
            'pagination' => $pagination,
            'keyWord' => $keyWord,
            'navbar' => 'task'
        ));
    }

    /**
     * [index description]
     * @Route("/tasks/new", name="new_task")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function newAction(Request $request) {
        $task = New Task();
        $form = $this->getTaskForm($task, $this->generateUrl('new_task'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setStatus('new');
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $this->saveTask($task);
            return $this->redirectToRoute('tasks');
        }

        return $this->render('task/new.html.twig', array(
            'form' => $form->createView(),
            'navbar' => 'task'
        ));
    }

    /**
     * @Route("/tasks/{task}/eidt", name="edit_task")
     * @param  Task $task
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->getTaskForm(
            $task,
            $this->generateUrl(
                'edit_task',
                ['task' => $task->getId()]
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $this->saveTask($item);

            return $this->redirectToRoute('tasks');
        }

        return $this->render('task/new.html.twig', array(
            'form' => $form->createView(),
            'navbar' => 'task'
        ));
    }

    /**
     * [getTaskForm description]
     * @param  Task   $task [description]
     * @param  [type] $path [description]
     * @return [type]       [description]
     */
    private function getTaskForm(Task $task, $path)
    {
        return $this->createFormBuilder($task)
            ->setAction($path)
            ->setMethod('POST')
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Device Roll In' => 'device_roll_in',
                    'Device Roll Out' => 'device_roll_out',
                ],
            ])
            ->add('content', TextType::class)
            ->add('delivery', TextType::class, ['required' => false])
            ->add('due_date', DateType::class)
            ->add('status', HiddenType::class)
            ->add('has_ct_access', ChoiceType::class, [
                'choices'  => [
                    'Yes' => true,
                    'No' => false,
                ],
            ])
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();
    }

    private function saveTask($task)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($task);
        $entityManager->flush();
    }

    /**
     * [getRepository description]
     * @return [type] [description]
     */
    private function getRepository () {
        return $this->getDoctrine()->getRepository(Task::class);
    }
}
