<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Task;


class IndexController extends Controller
{
    /**
     * [index description]
     * @Route("/index")
     * @return [type] [description]
     */
    public function index()
    {
        $task = New Task();

        return $this->render('index/index.html.twig', array(
            'tasks' => $task->findAll()
        ));
    }

    /**
     * [index description]
     * @Route("/new")
     * @return [type] [description]
     */
    public function new(Request $request) {
        $task = New Task();
        $form = $this->createFormBuilder($task)
            ->add('content', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute('task_success');
        }

        return $this->render('index/index.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
