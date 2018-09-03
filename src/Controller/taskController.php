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


class taskController extends Controller
{
    /**
     * [index description]
     * @Route("/tasks", name="tasks")
     * @return [type] [description]
     */
    public function getIndexAction()
    {
        $task = New Task();

        return $this->render('task/index.html.twig', array(
            'tasks' => $this->getRepository()->findBy([],['due_date'=>'desc'])
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
        $form = $this->createFormBuilder($task)
            ->setAction($this->generateUrl('new_task'))
            ->setMethod('POST')
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

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setStatus('new');
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('tasks');
        }

        return $this->render('task/new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * [index description]
     * @Route("/tasks/create", name="create_task")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function treat_create_task(Request $request) {

    }

    private function getRepository () {
        return $this->getDoctrine()->getRepository(Task::class);
    }
}
